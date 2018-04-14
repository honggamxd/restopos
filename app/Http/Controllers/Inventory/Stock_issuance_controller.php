<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use PDF;
use Validator;

use App\Inventory\Inventory_stock_issuance;
use App\Inventory\Inventory_stock_issuance_detail;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_stock_issuance_transformer;

class Stock_issuance_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_stock_issuance::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_stock_issuance_transformer)->parseIncludes('details.inventory_item,inventory_receiving_report')->toArray();
        // return $data;
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.stock-issuance', $data);
        // return view('pdf.stock-issuance');
        return $pdf->stream($data['stock_issuance_number_formatted'].'-stock-issuance-'.Carbon::parse($data['stock_issuance_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.stock-issuance-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_stock_issuance::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->orWhere('stock_issuance_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        if($request->autocomplete){
            if($request->approved=='1'){
                $result->whereNotNull('approved_by_name');
            }
        }
        $number_of_pages = 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_stock_issuance_transformer);
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        $form_number = Inventory_stock_issuance::orderBy('stock_issuance_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->stock_issuance_number : 1;
        return view('inventory.stock-issuance-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_stock_issuance::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_stock_issuance_transformer)->parseIncludes('details.inventory_item,inventory_receiving_report')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        // return $data;
        return view('inventory.stock-issuance-form',$data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'stock_issuance_number' => 'required|numeric|unique:inventory_stock_issuance,stock_issuance_number,NULL,id,deleted_at,NULL',
                'stock_issuance_date' => 'required|date',
                'requesting_department' => 'required',
                'request_chargeable_to' => 'required',
                // 'supplier_name' => 'required',
                'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'received_by_name' => 'required_with:received_by_name,received_by_date',
                'received_by_date' => 'required_with:received_by_name,received_by_date',
                'issued_by_name' => 'required_with:issued_by_name,issued_by_date',
                'issued_by_date' => 'required_with:issued_by_name,issued_by_date',
                'approved_by_name' => 'required_with:approved_by_name,approved_by_date',
                'approved_by_date' => 'required_with:approved_by_name,approved_by_date',
                'posted_by_name' => 'required_with:posted_by_name,posted_by_date',
                'posted_by_date' => 'required_with:posted_by_name,posted_by_date',
            ],
            [
                'received_by_name.required_with' => 'Required if the name or date is filled.',
                'received_by_date.required_with' => 'Required if the name or date is filled.',
                'issued_by_name.required_with' => 'Required if the name or date is filled.',
                'issued_by_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_date.required_with' => 'Required if the name or date is filled.',
                'posted_by_name.required_with' => 'Required if the name or date is filled.',
                'posted_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $stock_issuance = new Inventory_stock_issuance;
            $stock_issuance->stock_issuance_number = $request->stock_issuance_number;
            $stock_issuance->stock_issuance_date = $request->stock_issuance_date != null ? Carbon::parse($request->stock_issuance_date) : null;
            $stock_issuance->requesting_department = $request->requesting_department;
            $stock_issuance->request_chargeable_to = $request->request_chargeable_to;
            $stock_issuance->supplier_name = $request->supplier_name;
            $stock_issuance->supplier_address = $request->supplier_address;
            $stock_issuance->supplier_tin = $request->supplier_tin;
            $stock_issuance->received_by_name = $request->received_by_name;
            $stock_issuance->received_by_date = $request->received_by_date != null ? Carbon::parse($request->received_by_date) : null;
            $stock_issuance->issued_by_name = $request->issued_by_name;
            $stock_issuance->issued_by_date = $request->issued_by_date != null ? Carbon::parse($request->issued_by_date) : null;
            // $stock_issuance->approved_by_name = $request->approved_by_name;
            // $stock_issuance->approved_by_date = $request->approved_by_date != null ? Carbon::parse($request->approved_by_date) : null;
            $stock_issuance->posted_by_name = $request->posted_by_name;
            $stock_issuance->posted_by_date = $request->posted_by_date != null ? Carbon::parse($request->posted_by_date) : null;
            $stock_issuance->inventory_receiving_report_id = $request->inventory_receiving_report_id;
            $stock_issuance->save();

            $stock_issuance = Inventory_stock_issuance::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $stock_issuance_detail = new Inventory_stock_issuance_detail;
                $stock_issuance_detail->inventory_stock_issuance_id = $stock_issuance->id;
                $stock_issuance_detail->inventory_item_id = $form_item['id'];
                $stock_issuance_detail->unit_price = $form_item['unit_cost'];
                $stock_issuance_detail->quantity = $form_item['quantity'];
                $stock_issuance_detail->remarks = $form_item['remarks'];
                $stock_issuance_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $stock_issuance;
        # code...
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'stock_issuance_number' => 'required|numeric|unique:inventory_stock_issuance,stock_issuance_number,'.$id.',id,deleted_at,NULL',
                'stock_issuance_date' => 'required|date',
                'requesting_department' => 'required',
                'request_chargeable_to' => 'required',
                // 'supplier_name' => 'required',
                'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'received_by_name' => 'required_with:received_by_name,received_by_date',
                'received_by_date' => 'required_with:received_by_name,received_by_date',
                'issued_by_name' => 'required_with:issued_by_name,issued_by_date',
                'issued_by_date' => 'required_with:issued_by_name,issued_by_date',
                'approved_by_name' => 'required_with:approved_by_name,approved_by_date',
                'approved_by_date' => 'required_with:approved_by_name,approved_by_date',
                'posted_by_name' => 'required_with:posted_by_name,posted_by_date',
                'posted_by_date' => 'required_with:posted_by_name,posted_by_date',
            ],
            [
                'received_by_name.required_with' => 'Required if the name or date is filled.',
                'received_by_date.required_with' => 'Required if the name or date is filled.',
                'issued_by_name.required_with' => 'Required if the name or date is filled.',
                'issued_by_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_date.required_with' => 'Required if the name or date is filled.',
                'posted_by_name.required_with' => 'Required if the name or date is filled.',
                'posted_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        $validator->after(function ($validator) use ($request){
            if($request->approved_by_name != null){
                foreach ($request->items as $form_item) {
                    $item = fractal(Inventory_item::find($form_item['inventory_item_id']), new Inventory_item_transformer)->toArray();
                    if($item['total_quantity']<$form_item['quantity']){
                        $validator->errors()->add('items.'.$form_item['key'].'.error', 'Available: '.$item['total_quantity']);
                    }
                }
            }
        });
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $stock_issuance = Inventory_stock_issuance::findOrFail($id);
            $old_data = $stock_issuance;
            $stock_issuance->stock_issuance_number = $request->stock_issuance_number;
            $stock_issuance->stock_issuance_date = $request->stock_issuance_date != null ? Carbon::parse($request->stock_issuance_date) : null;
            $stock_issuance->requesting_department = $request->requesting_department;
            $stock_issuance->request_chargeable_to = $request->request_chargeable_to;
            $stock_issuance->supplier_name = $request->supplier_name;
            $stock_issuance->supplier_address = $request->supplier_address;
            $stock_issuance->supplier_tin = $request->supplier_tin;
            $stock_issuance->received_by_name = $request->received_by_name;
            $stock_issuance->received_by_date = $request->received_by_date != null ? Carbon::parse($request->received_by_date) : null;
            $stock_issuance->issued_by_name = $request->issued_by_name;
            $stock_issuance->issued_by_date = $request->issued_by_date != null ? Carbon::parse($request->issued_by_date) : null;
            $stock_issuance->approved_by_name = $request->approved_by_name;
            $stock_issuance->approved_by_date = $request->approved_by_date != null ? Carbon::parse($request->approved_by_date) : null;
            $stock_issuance->posted_by_name = $request->posted_by_name;
            $stock_issuance->posted_by_date = $request->posted_by_date != null ? Carbon::parse($request->posted_by_date) : null;
            $stock_issuance->save();

            foreach ($request->items as $form_item) {
                $stock_issuance_detail = Inventory_stock_issuance_detail::find($form_item['id']);
                $stock_issuance_detail->unit_price = $form_item['unit_cost'];
                $stock_issuance_detail->quantity = $form_item['quantity'];
                $stock_issuance_detail->remarks = $form_item['remarks'];
                $stock_issuance_detail->save();

                $item_detail = Inventory_item_detail::where('inventory_item_id',$form_item['inventory_item_id'])->where('inventory_stock_issuance_id',$id)->first();
                if($item_detail == null){
                    if($request->approved_by_name != null){
                        $item_detail = new Inventory_item_detail;
                        $item_detail->inventory_item_id = $stock_issuance_detail->inventory_item_id;
                        $item_detail->unit_cost = $form_item['unit_cost'];
                        $item_detail->quantity = $form_item['quantity'] * -1;
                        $item_detail->inventory_stock_issuance_id = $stock_issuance->id;
                        $item_detail->save();
                    }
                }else{
                    $item_detail = Inventory_item_detail::where('inventory_item_id',$stock_issuance_detail->inventory_item_id)->where('inventory_stock_issuance_id',$id)->first();
                    $item_detail->unit_cost = $form_item['unit_cost'];
                    $item_detail->save();
                }
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $stock_issuance;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $stock_issuance = Inventory_stock_issuance::findOrFail($id);
            $stock_issuance_detail = Inventory_stock_issuance_detail::where('inventory_stock_issuance_id',$id);
            Inventory_item_detail::where('inventory_stock_issuance_id',$id)->delete();
            $stock_issuance->delete();
            $stock_issuance_detail->delete();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        // return $stock_issuance;
    }
}
