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

use App\Inventory\Inventory_receiving_report;
use App\Inventory\Inventory_receiving_report_detail;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_receiving_report_transformer;

class Receiving_report_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_receiving_report::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_receiving_report_transformer)->parseIncludes('details.inventory_item,inventory_purchase_order.inventory_purchase_request')->toArray();
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.receiving-report', $data);
        // return $data;
        // return view('pdf.receiving-report');
        return $pdf->stream($data['receiving_report_number_formatted'].'-receiving-report-'.Carbon::parse($data['receiving_report_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.receiving-report-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_receiving_report::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->orWhere('receiving_report_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        $number_of_pages = 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_receiving_report_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        $form_number = Inventory_receiving_report::orderBy('receiving_report_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->receiving_report_number : 1;
        return view('inventory.receiving-report-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_receiving_report::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_receiving_report_transformer)->parseIncludes('details.inventory_item,inventory_purchase_order')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        // return $data;
        return view('inventory.receiving-report-form',$data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                // 'receiving_report_number' => 'required|numeric|unique:inventory_receiving_report,receiving_report_number,NULL,id,deleted_at,NULL',
                'receiving_report_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                // 'supplier_contact_number' => 'required',
                // 'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
                'received_by_name' => 'required_with:received_by_name,received_by_date',
                'received_by_date' => 'required_with:received_by_name,received_by_date',
                'checked_by_name' => 'required_with:checked_by_name,checked_by_date',
                'checked_by_date' => 'required_with:checked_by_name,checked_by_date',
                'posted_by_name' => 'required_with:posted_by_name,posted_by_date',
                'posted_by_date' => 'required_with:posted_by_name,posted_by_date',
            ],
            [
                'received_by_name.required_with' => 'Required if the name or date is filled.',
                'received_by_date.required_with' => 'Required if the name or date is filled.',
                'checked_by_name.required_with' => 'Required if the name or date is filled.',
                'checked_by_date.required_with' => 'Required if the name or date is filled.',
                'posted_by_name.required_with' => 'Required if the name or date is filled.',
                'posted_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $receiving_report = new Inventory_receiving_report;
            // $receiving_report->receiving_report_number = $request->receiving_report_number;
            $receiving_report->receiving_report_date = $request->receiving_report_date!=null ? Carbon::parse($request->receiving_report_date) : null;
            $receiving_report->supplier_name = $request->supplier_name;
            $receiving_report->supplier_address = $request->supplier_address;
            $receiving_report->supplier_tin = $request->supplier_tin;
            $receiving_report->supplier_contact_number = $request->supplier_contact_number;
            $receiving_report->term = $request->term;
            $receiving_report->requesting_department = $request->requesting_department;
            $receiving_report->purpose = $request->purpose;
            $receiving_report->request_chargeable_to = $request->request_chargeable_to;
            $receiving_report->received_by_name = $request->received_by_name;
            $receiving_report->received_by_date = $request->received_by_date!=null ? Carbon::parse($request->received_by_date) : null;
            $receiving_report->checked_by_name = $request->checked_by_name;
            $receiving_report->checked_by_date = $request->checked_by_date!=null ? Carbon::parse($request->checked_by_date) : null;
            $receiving_report->posted_by_name = $request->posted_by_name;
            $receiving_report->posted_by_date = $request->posted_by_date!=null ? Carbon::parse($request->posted_by_date) : null;
            $receiving_report->inventory_purchase_order_id = $request->inventory_purchase_order_id;
            $receiving_report->save();

            $receiving_report = Inventory_receiving_report::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $receiving_report_detail = new Inventory_receiving_report_detail;
                $receiving_report_detail->inventory_receiving_report_id = $receiving_report->id;
                $receiving_report_detail->inventory_item_id = $form_item['id'];
                $receiving_report_detail->unit_price = $form_item['unit_cost'];
                $receiving_report_detail->quantity = $form_item['quantity'];
                $receiving_report_detail->remarks = $form_item['remarks'];
                $receiving_report_detail->save();

                $item_detail = new Inventory_item_detail;
                $item_detail->inventory_item_id = $form_item['id'];
                $item_detail->unit_cost = $form_item['unit_cost'];
                $item_detail->quantity = $form_item['quantity'];
                $item_detail->inventory_receiving_report_id = $receiving_report->id;
                $item_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $receiving_report;
        # code...
    }

    public function update(Request $request,$id)
    {
        $this->validate(
            $request,
            [
                // 'receiving_report_number' => 'required|numeric|unique:inventory_receiving_report,receiving_report_number,'.$id.',id,deleted_at,NULL',
                'receiving_report_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                // 'supplier_contact_number' => 'required',
                // 'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
                'received_by_name' => 'required_with:received_by_name,received_by_date',
                'received_by_date' => 'required_with:received_by_name,received_by_date',
                'checked_by_name' => 'required_with:checked_by_name,checked_by_date',
                'checked_by_date' => 'required_with:checked_by_name,checked_by_date',
                'posted_by_name' => 'required_with:posted_by_name,posted_by_date',
                'posted_by_date' => 'required_with:posted_by_name,posted_by_date',
            ],
            [
                'received_by_name.required_with' => 'Required if the name or date is filled.',
                'received_by_date.required_with' => 'Required if the name or date is filled.',
                'checked_by_name.required_with' => 'Required if the name or date is filled.',
                'checked_by_date.required_with' => 'Required if the name or date is filled.',
                'posted_by_name.required_with' => 'Required if the name or date is filled.',
                'posted_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            // return $request->all();
            $receiving_report = Inventory_receiving_report::findOrFail($id);
            // $receiving_report->receiving_report_number = $request->receiving_report_number;
            $receiving_report->receiving_report_date = $request->receiving_report_date!=null ? Carbon::parse($request->receiving_report_date) : null;
            $receiving_report->supplier_name = $request->supplier_name;
            $receiving_report->supplier_address = $request->supplier_address;
            $receiving_report->supplier_tin = $request->supplier_tin;
            $receiving_report->supplier_contact_number = $request->supplier_contact_number;
            $receiving_report->term = $request->term;
            $receiving_report->requesting_department = $request->requesting_department;
            $receiving_report->purpose = $request->purpose;
            $receiving_report->request_chargeable_to = $request->request_chargeable_to;
            $receiving_report->received_by_name = $request->received_by_name;
            $receiving_report->received_by_date = $request->received_by_date!=null ? Carbon::parse($request->received_by_date) : null;
            $receiving_report->checked_by_name = $request->checked_by_name;
            $receiving_report->checked_by_date = $request->checked_by_date!=null ? Carbon::parse($request->checked_by_date) : null;
            $receiving_report->posted_by_name = $request->posted_by_name;
            $receiving_report->posted_by_date = $request->posted_by_date!=null ? Carbon::parse($request->posted_by_date) : null;
            $receiving_report->save();

            $receiving_report = Inventory_receiving_report::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $receiving_report_detail = Inventory_receiving_report_detail::find($form_item['id']);
                $receiving_report_detail->unit_price = $form_item['unit_cost'];
                $receiving_report_detail->quantity = $form_item['quantity'];
                $receiving_report_detail->remarks = $form_item['remarks'];
                $receiving_report_detail->save();

                $item_detail = Inventory_item_detail::where('inventory_item_id',$receiving_report_detail->inventory_item_id)->where('inventory_receiving_report_id',$id)->first();
                $item_detail->unit_cost = $form_item['unit_cost'];
                $item_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $receiving_report;
    }

    public function destroy($id)
    {
        $validator = Validator::make([],[]);
        $validator->after(function ($validator) use ($id){
            $receiving_report_detail = Inventory_receiving_report_detail::where('inventory_receiving_report_id',$id);
            $results = $receiving_report_detail->get();
            foreach ($results as $key => $receiving_report_item) {
                $item = fractal(Inventory_item::find($receiving_report_item['inventory_item_id']), new Inventory_item_transformer)->toArray();
                if($item['total_quantity']<$receiving_report_item['quantity']){
                    $validator->errors()->add('items.'.$key.'.error', 'Remaining quantity: '.$item['total_quantity']);
                }
            }
        });
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $receiving_report = Inventory_receiving_report::findOrFail($id);
            $receiving_report_detail = Inventory_receiving_report_detail::where('inventory_receiving_report_id',$id);
            Inventory_item_detail::where('inventory_receiving_report_id',$id)->delete();
            $receiving_report->delete();
            $receiving_report_detail->delete();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
    }
}
