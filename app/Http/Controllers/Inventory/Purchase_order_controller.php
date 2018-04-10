<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use PDF;

use App\Inventory\Inventory_purchase_order;
use App\Inventory\Inventory_purchase_order_detail;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_purchase_order_transformer;

class Purchase_order_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_purchase_order::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_purchase_order_transformer)->parseIncludes('details.inventory_item,inventory_purchase_request')->toArray();
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.purchase-order', $data);
        // return $data;
        // return view('pdf.purchase-order');
        return $pdf->stream($data['purchase_order_number_formatted'].'-purchase-order-'.Carbon::parse($data['purchase_order_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.purchase-order-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_purchase_order::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->where('purchase_order_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        if($request->autocomplete){
            if($request->approved=='1'){
                $result->whereNotNull('approved_by_name');
            }
        }
        $number_of_pages = $request->autocomplete ? 10 : 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_purchase_order_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        return view('inventory.purchase-order-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_purchase_order::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_purchase_order_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        return view('inventory.purchase-order-form',$data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'purchase_order_number' => 'required|numeric|unique:inventory_purchase_order,purchase_order_number,NULL,id,deleted_at,NULL',
                'purchase_order_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
                'requested_by_name' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            $purchase_order = new Inventory_purchase_order;
            $purchase_order->purchase_order_number = $request->purchase_order_number;
            $purchase_order->purchase_order_date = Carbon::parse($request->purchase_order_date);
            $purchase_order->supplier_name = $request->supplier_name;
            $purchase_order->supplier_address = $request->supplier_address;
            $purchase_order->supplier_tin = $request->supplier_tin;
            $purchase_order->term = $request->term;
            $purchase_order->requesting_department = $request->requesting_department;
            $purchase_order->purpose = $request->purpose;
            $purchase_order->request_chargeable_to = $request->request_chargeable_to;
            $purchase_order->requested_by_name = $request->requested_by_name;
            $purchase_order->noted_by_name = $request->noted_by_name;
            $purchase_order->approved_by_name = $request->approved_by_name;
            $purchase_order->inventory_purchase_request_id = $request->inventory_purchase_request_id;
            $purchase_order->save();

            $purchase_order = Inventory_purchase_order::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $purchase_order_detail = new Inventory_purchase_order_detail;
                $purchase_order_detail->inventory_purchase_order_id = $purchase_order->id;
                $purchase_order_detail->inventory_item_id = $form_item['id'];
                $purchase_order_detail->unit_price = $form_item['unit_cost'];
                $purchase_order_detail->quantity = $form_item['quantity'];
                $purchase_order_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $purchase_order;
        # code...
    }

    public function update(Request $request,$id)
    {
          $this->validate(
            $request,
            [
                'purchase_order_number' => 'required|numeric|unique:inventory_purchase_order,purchase_order_number,'.$id.',id,deleted_at,NULL',
                'purchase_order_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
                'requested_by_name' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            // return $request->all();
            $purchase_order = Inventory_purchase_order::findOrFail($id);
            $purchase_order->purchase_order_number = $request->purchase_order_number;
            $purchase_order->purchase_order_date = Carbon::parse($request->purchase_order_date);
            $purchase_order->supplier_name = $request->supplier_name;
            $purchase_order->supplier_address = $request->supplier_address;
            $purchase_order->supplier_tin = $request->supplier_tin;
            $purchase_order->term = $request->term;
            $purchase_order->requesting_department = $request->requesting_department;
            $purchase_order->purpose = $request->purpose;
            $purchase_order->request_chargeable_to = $request->request_chargeable_to;
            $purchase_order->requested_by_name = $request->requested_by_name;
            $purchase_order->noted_by_name = $request->noted_by_name;
            $purchase_order->approved_by_name = $request->approved_by_name;
            $purchase_order->save();

            $purchase_order = Inventory_purchase_order::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $purchase_order_detail = Inventory_purchase_order_detail::find($form_item['id']);
                $purchase_order_detail->unit_price = $form_item['unit_cost'];
                $purchase_order_detail->quantity = $form_item['quantity'];
                $purchase_order_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $purchase_order;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $purchase_order = Inventory_purchase_order::findOrFail($id);
            $purchase_order_detail = Inventory_purchase_order_detail::where('inventory_purchase_order_id',$id);
            $purchase_order->delete();
            $purchase_order_detail->delete();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
    }
}
