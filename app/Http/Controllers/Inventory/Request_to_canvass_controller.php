<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use PDF;

use App\Inventory\Inventory_request_to_canvass;
use App\Inventory\Inventory_request_to_canvass_detail;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_request_to_canvass_transformer;

class Request_to_canvass_controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->check_json_settings();
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_request_to_canvass::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_request_to_canvass_transformer)->parseIncludes('details.inventory_item,inventory_purchase_request')->toArray();
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.request-to-canvass', $data);
        // return $data;
        // return view('pdf.request-to-canvass');
        return $pdf->stream($data['request_to_canvass_number_formatted'].'-request-to-canvass-'.Carbon::parse($data['request_to_canvass_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.request-to-canvass-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_request_to_canvass::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->orWhere('request_to_canvass_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        $number_of_pages = $request->autocomplete ? 10 : 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_request_to_canvass_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        $form_number = Inventory_request_to_canvass::orderBy('request_to_canvass_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->request_to_canvass_number : 1;
        return view('inventory.request-to-canvass-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_request_to_canvass::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_request_to_canvass_transformer)->parseIncludes('details.inventory_item,inventory_purchase_request')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        return view('inventory.request-to-canvass-form',$data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                // 'request_to_canvass_number' => 'required|numeric|unique:inventory_request_to_canvass,request_to_canvass_number,NULL,id,deleted_at,NULL',
                'request_to_canvass_date' => 'required|date',
                'requesting_department' => 'required',
                'reason_for_the_request' => 'required',
                'type_of_item_requested' => 'required',
                'requested_by_name' => 'required',
                // 'noted_by_name' => 'required',
                // 'canvass_by_name' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            $request_to_canvass = new Inventory_request_to_canvass;
            // $request_to_canvass->request_to_canvass_number = $request->request_to_canvass_number;
            $request_to_canvass->request_to_canvass_date = Carbon::parse($request->request_to_canvass_date);
            $request_to_canvass->requesting_department = $request->requesting_department;
            $request_to_canvass->reason_for_the_request = $request->reason_for_the_request;
            $request_to_canvass->type_of_item_requested = $request->type_of_item_requested;
            $request_to_canvass->requested_by_name = $request->requested_by_name;
            $request_to_canvass->noted_by_name = $request->noted_by_name;
            $request_to_canvass->canvass_by_name = $request->canvass_by_name;
            $request_to_canvass->vendor_1_name = $request->vendor_1_name;
            $request_to_canvass->vendor_2_name = $request->vendor_2_name;
            $request_to_canvass->vendor_3_name = $request->vendor_3_name;
            $request_to_canvass->inventory_purchase_request_id = $request->inventory_purchase_request_id;
            $request_to_canvass->save();

            $request_to_canvass = Inventory_request_to_canvass::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $request_to_canvass_detail = new Inventory_request_to_canvass_detail;
                $request_to_canvass_detail->inventory_request_to_canvass_id = $request_to_canvass->id;
                $request_to_canvass_detail->inventory_item_id = $form_item['id'];
                $request_to_canvass_detail->quantity = $form_item['quantity'];
                $request_to_canvass_detail->vendor_1_unit_price = $form_item['vendor_1_unit_price'];
                $request_to_canvass_detail->vendor_2_unit_price = $form_item['vendor_2_unit_price'];
                $request_to_canvass_detail->vendor_3_unit_price = $form_item['vendor_3_unit_price'];
                $request_to_canvass_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $request_to_canvass;
        # code...
    }

    public function update(Request $request,$id)
    {
          $this->validate(
            $request,
            [
                // 'request_to_canvass_number' => 'required|numeric|unique:inventory_request_to_canvass,request_to_canvass_number,'.$id.',id,deleted_at,NULL',
                'request_to_canvass_date' => 'required|date',
                'requesting_department' => 'required',
                'reason_for_the_request' => 'required',
                'type_of_item_requested' => 'required',
                'requested_by_name' => 'required',
                // 'noted_by_name' => 'required',
                // 'canvass_by_name' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            // return $request->all();
            $request_to_canvass = Inventory_request_to_canvass::findOrFail($id);
            // $request_to_canvass->request_to_canvass_number = $request->request_to_canvass_number;
            $request_to_canvass->request_to_canvass_date = Carbon::parse($request->request_to_canvass_date);
            $request_to_canvass->requesting_department = $request->requesting_department;
            $request_to_canvass->reason_for_the_request = $request->reason_for_the_request;
            $request_to_canvass->type_of_item_requested = $request->type_of_item_requested;
            $request_to_canvass->requested_by_name = $request->requested_by_name;
            $request_to_canvass->noted_by_name = $request->noted_by_name;
            $request_to_canvass->canvass_by_name = $request->canvass_by_name;
            $request_to_canvass->vendor_1_name = $request->vendor_1_name;
            $request_to_canvass->vendor_2_name = $request->vendor_2_name;
            $request_to_canvass->vendor_3_name = $request->vendor_3_name;
            $request_to_canvass->save();

            $request_to_canvass = Inventory_request_to_canvass::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $request_to_canvass_detail = Inventory_request_to_canvass_detail::find($form_item['id']);
                $request_to_canvass_detail->vendor_1_unit_price = $form_item['vendor_1_unit_price'];
                $request_to_canvass_detail->vendor_2_unit_price = $form_item['vendor_2_unit_price'];
                $request_to_canvass_detail->vendor_3_unit_price = $form_item['vendor_3_unit_price'];
                $request_to_canvass_detail->quantity = $form_item['quantity'];
                $request_to_canvass_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $request_to_canvass;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $request_to_canvass = Inventory_request_to_canvass::findOrFail($id);
            $request_to_canvass_detail = Inventory_request_to_canvass_detail::where('inventory_request_to_canvass_id',$id);
            $request_to_canvass->delete();
            $request_to_canvass_detail->delete();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
    }

    public function settings(Request $request)
    {
        return view('inventory.request-to-canvass-settings');
    }

    private function check_json_settings()
    {
        if(!file_exists(public_path('settings/request-to-canvass.json'))){
            $data = array();
            $data['footer'] = ['noted_by_name'=>[]];
            $fp = fopen('settings/request-to-canvass.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }

    public function update_footer_settings(Request $request)
    {
        $data = array();
        $data['footer'] = ['noted_by_name'=>$request->noted_by_name,'canvass_by_name'=>$request->canvass_by_name];
        $fp = fopen('settings/request-to-canvass.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    public function get_footer_settings(Request $request)
    {
        $string = file_get_contents(public_path("settings/request-to-canvass.json"));
        return $string;
    }
}
