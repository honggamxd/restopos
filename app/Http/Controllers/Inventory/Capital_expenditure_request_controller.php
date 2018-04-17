<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use PDF;

use App\Inventory\Inventory_capital_expenditure_request;
use App\Inventory\Inventory_capital_expenditure_request_detail;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_capital_expenditure_request_transformer;

class Capital_expenditure_request_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_capital_expenditure_request::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_capital_expenditure_request_transformer)->parseIncludes('details.inventory_item,inventory_purchase_request')->toArray();
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.capital-expenditure-request', $data);
        // return $data;
        // return view('pdf.capital-expenditure-request');
        return $pdf->stream($data['capital_expenditure_request_number_formatted'].'-capital-expenditure-request-'.Carbon::parse($data['capital_expenditure_request_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.capital-expenditure-request-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_capital_expenditure_request::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->orWhere('capital_expenditure_request_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        if($request->autocomplete){
            $result->whereNotNull('approved_by_name');
        }
        $number_of_pages = $request->autocomplete ? 10 : 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_capital_expenditure_request_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        $form_number = Inventory_capital_expenditure_request::orderBy('capital_expenditure_request_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->capital_expenditure_request_number : 1;
        return view('inventory.capital-expenditure-request-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_capital_expenditure_request::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_capital_expenditure_request_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        return view('inventory.capital-expenditure-request-form',$data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                // 'capital_expenditure_request_number' => 'required|numeric|unique:inventory_capital_expenditure_request,capital_expenditure_request_number,NULL,id,deleted_at,NULL',
                'capital_expenditure_request_date' => 'required|date',
                // 'budget_description' => 'required',
                // 'budget_amount' => 'required',
                'department' => 'required',
                // 'source_of_funds' => 'required',
                // 'brief_project_description' => 'required',
                // 'purpose' => 'required',
                'requested_by_name' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_date' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_position' => 'required',
                'approved_by_1_name' => 'required_with:approved_by_1_name,approved_by_1_date',
                'approved_by_1_date' => 'required_with:approved_by_1_name,approved_by_1_date',
                'approved_by_1_position' => 'required',
                'approved_by_2_name' => 'required_with:approved_by_2_name,approved_by_2_date',
                'approved_by_2_date' => 'required_with:approved_by_2_name,approved_by_2_date',
                'approved_by_2_position' => 'required',
                'verified_as_funded_by_name' => 'required_with:verified_as_funded_by_name,verified_as_funded_by_date',
                'verified_as_funded_by_date' => 'required_with:verified_as_funded_by_name,verified_as_funded_by_date',
                'verified_as_funded_by_position' => 'required',
                'recorded_by_name' => 'required_with:recorded_by_name,recorded_by_date',
                'recorded_by_date' => 'required_with:recorded_by_name,recorded_by_date',
                'recorded_by_position' => 'required',
            ],
            [
                'capital_expenditure_request_number.required' => 'The budget number field is required.',
                'capital_expenditure_request_number.numeric' => 'The budget number must be numeric.',
                'capital_expenditure_request_number.unique' => 'The budget number is already in the list.',
                'capital_expenditure_request_date.required' => 'The date field is required.',
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
                'requested_by_date.required_with' => 'Required if the name or date is filled.',
                'requested_by_position.required' => 'The position field is required.',
                'approved_by_1_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_1_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_1_position.required' => 'The position field is required.',
                'approved_by_2_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_2_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_2_position.required' => 'The position field is required.',
                'verified_as_funded_by_name.required_with' => 'Required if the name or date is filled.',
                'verified_as_funded_by_date.required_with' => 'Required if the name or date is filled.',
                'verified_as_funded_by_position.required' => 'The position field is required.',
                'recorded_by_name.required_with' => 'Required if the name or date is filled.',
                'recorded_by_date.required_with' => 'Required if the name or date is filled.',
                'recorded_by_position.required' => 'The position field is required.',
            ]
        );
        DB::beginTransaction();
        try{
            $capital_expenditure_request = new Inventory_capital_expenditure_request;
            // $capital_expenditure_request->capital_expenditure_request_number = $request->capital_expenditure_request_number;
            $capital_expenditure_request->capital_expenditure_request_date = Carbon::parse($request->capital_expenditure_request_date);
            $capital_expenditure_request->budget_description = $request->budget_description;
            $capital_expenditure_request->budget_amount = $request->budget_amount ? $request->budget_amount : 0;
            $capital_expenditure_request->department = $request->department;
            $capital_expenditure_request->source_of_funds = $request->source_of_funds;
            $capital_expenditure_request->brief_project_description = $request->brief_project_description;
            $capital_expenditure_request->purpose = $request->purpose;
            $capital_expenditure_request->requested_by_name = $request->requested_by_name;
            $capital_expenditure_request->requested_by_date = $request->requested_by_date !=null ? Carbon::parse($request->requested_by_date) : null;
            $capital_expenditure_request->requested_by_position = $request->requested_by_position;
            $capital_expenditure_request->approved_by_1_name = $request->approved_by_1_name;
            $capital_expenditure_request->approved_by_1_date = $request->approved_by_1_date !=null ? Carbon::parse($request->approved_by_1_date) : null;
            $capital_expenditure_request->approved_by_1_position = $request->approved_by_1_position;
            $capital_expenditure_request->approved_by_2_name = $request->approved_by_2_name;
            $capital_expenditure_request->approved_by_2_date = $request->approved_by_2_date !=null ? Carbon::parse($request->approved_by_2_date) : null;
            $capital_expenditure_request->approved_by_2_position = $request->approved_by_2_position;
            $capital_expenditure_request->verified_as_funded_by_name = $request->verified_as_funded_by_name;
            $capital_expenditure_request->verified_as_funded_by_date = $request->verified_as_funded_by_date !=null ? Carbon::parse($request->verified_as_funded_by_date) : null;
            $capital_expenditure_request->verified_as_funded_by_position = $request->verified_as_funded_by_position;
            $capital_expenditure_request->recorded_by_name = $request->recorded_by_name;
            $capital_expenditure_request->recorded_by_date = $request->recorded_by_date !=null ? Carbon::parse($request->recorded_by_date) : null;
            $capital_expenditure_request->recorded_by_position = $request->recorded_by_position;
            $capital_expenditure_request->inventory_purchase_request_id = $request->inventory_purchase_request_id;
            $capital_expenditure_request->save();

            $capital_expenditure_request = Inventory_capital_expenditure_request::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $capital_expenditure_request_detail = new Inventory_capital_expenditure_request_detail;
                $capital_expenditure_request_detail->inventory_capital_expenditure_request_id = $capital_expenditure_request->id;
                $capital_expenditure_request_detail->inventory_item_id = $form_item['id'];
                $capital_expenditure_request_detail->unit_price = $form_item['unit_cost'];
                $capital_expenditure_request_detail->quantity = $form_item['quantity'];
                $capital_expenditure_request_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $capital_expenditure_request;
        # code...
    }

    public function update(Request $request,$id)
    {
          $this->validate(
            $request,
            [
                // 'capital_expenditure_request_number' => 'required|numeric|unique:inventory_capital_expenditure_request,capital_expenditure_request_number,'.$id.',id,deleted_at,NULL',
                'capital_expenditure_request_date' => 'required|date',
                // 'budget_description' => 'required',
                // 'budget_amount' => 'required',
                'department' => 'required',
                // 'source_of_funds' => 'required',
                // 'brief_project_description' => 'required',
                // 'purpose' => 'required',
                'requested_by_name' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_date' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_position' => 'required',
                'approved_by_1_name' => 'required_with:approved_by_1_name,approved_by_1_date',
                'approved_by_1_date' => 'required_with:approved_by_1_name,approved_by_1_date',
                'approved_by_1_position' => 'required',
                'approved_by_2_name' => 'required_with:approved_by_2_name,approved_by_2_date',
                'approved_by_2_date' => 'required_with:approved_by_2_name,approved_by_2_date',
                'approved_by_2_position' => 'required',
                'verified_as_funded_by_name' => 'required_with:verified_as_funded_by_name,verified_as_funded_by_date',
                'verified_as_funded_by_date' => 'required_with:verified_as_funded_by_name,verified_as_funded_by_date',
                'verified_as_funded_by_position' => 'required',
                'recorded_by_name' => 'required_with:recorded_by_name,recorded_by_date',
                'recorded_by_date' => 'required_with:recorded_by_name,recorded_by_date',
                'recorded_by_position' => 'required',
            ],
            [
                'capital_expenditure_request_number.required' => 'The budget number field is required.',
                'capital_expenditure_request_number.numeric' => 'The budget number must be numeric.',
                'capital_expenditure_request_number.unique' => 'The budget number is already in the list.',
                'capital_expenditure_request_date.required' => 'The date field is required.',
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
                'requested_by_date.required_with' => 'Required if the name or date is filled.',
                'requested_by_position.required' => 'The position field is required.',
                'approved_by_1_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_1_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_1_position.required' => 'The position field is required.',
                'approved_by_2_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_2_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_2_position.required' => 'The position field is required.',
                'verified_as_funded_by_name.required_with' => 'Required if the name or date is filled.',
                'verified_as_funded_by_date.required_with' => 'Required if the name or date is filled.',
                'verified_as_funded_by_position.required' => 'The position field is required.',
                'recorded_by_name.required_with' => 'Required if the name or date is filled.',
                'recorded_by_date.required_with' => 'Required if the name or date is filled.',
                'recorded_by_position.required' => 'The position field is required.',
            ]
        );
        DB::beginTransaction();
        try{
            // return $request->all();
            $capital_expenditure_request = Inventory_capital_expenditure_request::findOrFail($id);
            // $capital_expenditure_request->capital_expenditure_request_number = $request->capital_expenditure_request_number;
            $capital_expenditure_request->capital_expenditure_request_date = Carbon::parse($request->capital_expenditure_request_date);
            $capital_expenditure_request->budget_description = $request->budget_description;
            $capital_expenditure_request->budget_amount = $request->budget_amount ? $request->budget_amount : 0;
            $capital_expenditure_request->department = $request->department;
            $capital_expenditure_request->source_of_funds = $request->source_of_funds;
            $capital_expenditure_request->brief_project_description = $request->brief_project_description;
            $capital_expenditure_request->purpose = $request->purpose;
            $capital_expenditure_request->requested_by_name = $request->requested_by_name;
            $capital_expenditure_request->requested_by_date = $request->requested_by_date !=null ? Carbon::parse($request->requested_by_date) : null;
            $capital_expenditure_request->requested_by_position = $request->requested_by_position;
            $capital_expenditure_request->is_approved = $request->approved_by_date != null ? 1 : 0;
            $capital_expenditure_request->approved_by_1_name = $request->approved_by_1_name;
            $capital_expenditure_request->approved_by_1_date = $request->approved_by_1_date !=null ? Carbon::parse($request->approved_by_1_date) : null;
            $capital_expenditure_request->approved_by_1_position = $request->approved_by_1_position;
            $capital_expenditure_request->approved_by_2_name = $request->approved_by_2_name;
            $capital_expenditure_request->approved_by_2_date = $request->approved_by_2_date !=null ? Carbon::parse($request->approved_by_2_date) : null;
            $capital_expenditure_request->approved_by_2_position = $request->approved_by_2_position;
            $capital_expenditure_request->verified_as_funded_by_name = $request->verified_as_funded_by_name;
            $capital_expenditure_request->verified_as_funded_by_date = $request->verified_as_funded_by_date !=null ? Carbon::parse($request->verified_as_funded_by_date) : null;
            $capital_expenditure_request->verified_as_funded_by_position = $request->verified_as_funded_by_position;
            $capital_expenditure_request->recorded_by_name = $request->recorded_by_name;
            $capital_expenditure_request->recorded_by_date = $request->recorded_by_date !=null ? Carbon::parse($request->recorded_by_date) : null;
            $capital_expenditure_request->recorded_by_position = $request->recorded_by_position;
            $capital_expenditure_request->save();

            $capital_expenditure_request = Inventory_capital_expenditure_request::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $capital_expenditure_request_detail = Inventory_capital_expenditure_request_detail::find($form_item['id']);
                $capital_expenditure_request_detail->unit_price = $form_item['unit_cost'];
                $capital_expenditure_request_detail->quantity = $form_item['quantity'];
                $capital_expenditure_request_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $capital_expenditure_request;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $capital_expenditure_request = Inventory_capital_expenditure_request::findOrFail($id);
            $capital_expenditure_request_detail = Inventory_capital_expenditure_request_detail::where('inventory_capital_expenditure_request_id',$id);
            $capital_expenditure_request->delete();
            $capital_expenditure_request_detail->delete();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
    }
}
