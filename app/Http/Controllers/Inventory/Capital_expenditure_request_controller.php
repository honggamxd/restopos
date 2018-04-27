<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use PDF;
use App;
use Validator;

use App\Inventory\Inventory_capital_expenditure_request;
use App\Inventory\Inventory_capital_expenditure_request_detail;
use App\Inventory\Inventory_capital_expenditure_request_recipient;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;
use App\User;
use App\Transformers\User_transformer;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_capital_expenditure_request_transformer;
use App\Transformers\Inventory_capital_expenditure_request_recipient_transformer;
use App\Helpers\MailNotification;

class Capital_expenditure_request_controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->check_json_settings();
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
        $data['capital_expenditure_request'] = fractal($capital_expenditure_request, new Inventory_capital_expenditure_request_transformer)->toArray();
        $recipients = Inventory_capital_expenditure_request_recipient::query();
        $recipients->leftJoin('user','inventory_capital_expenditure_request_recipient.user_id','=','user.id');
        $recipients->select('inventory_capital_expenditure_request_recipient.*');
        $recipients->whereNotNull('user.email_address');
        $recipients->where('inventory_capital_expenditure_request_recipient.notify_email','1');
        $recipients = $recipients->get();
        $data['recipients'] = fractal($recipients, new Inventory_capital_expenditure_request_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
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

    public function approve(Request $request,$id)
    {
        $validator = Validator::make(
            [],
            []
        );
        $validator->after(function ($validator) use ($id){
            $capital_expenditure_request = Inventory_capital_expenditure_request::findOrFail($id);
            if($capital_expenditure_request->is_approved==1){
                $validator->errors()->add('error', 'The capital expenditure request form that you are going to approve is already approved by  '.$capital_expenditure_request->approved_by_1_name.' on '.Carbon::parse($capital_expenditure_request->approved_by_1_date)->format('F d, Y') );
            }
        });
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $settings = json_decode($this->get_footer_settings($request));
            $capital_expenditure_request = Inventory_capital_expenditure_request::findOrFail($id);
            $capital_expenditure_request->approved_by_1_name = Auth::user()->name;
            $capital_expenditure_request->approved_by_1_date = Carbon::now()->format('Y-m-d');
            $capital_expenditure_request->approved_by_2_name = $settings->footer->approved_by_2_name;
            $capital_expenditure_request->approved_by_2_date = Carbon::now()->format('Y-m-d');
            $capital_expenditure_request->is_approved = 1;
            $capital_expenditure_request->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($capital_expenditure_request, new Inventory_capital_expenditure_request_transformer)->parseIncludes('details.inventory_item')->toArray();
    }

    public function settings(Request $request)
    {
        $user = new User;

        $data['users'] = fractal(User::all(), new User_transformer)->parseIncludes('restaurant')->toArray();
        return view('inventory.capital-expenditure-request-settings',$data);
    }

    private function check_json_settings()
    {
        if(!file_exists(public_path('settings/capital-expenditure-request.json'))){
            $data = array();
            $data['footer'] = [
                'verified_as_funded_by_name'=>[],
                'approved_by_2_name'=>[],
                'recorded_by_name'=>[]
            ];
            $fp = fopen('settings/capital-expenditure-request.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }

    public function update_footer_settings(Request $request)
    {
        $data = array();
        $data['footer'] = [
            'verified_as_funded_by_name'=>$request->verified_as_funded_by_name,
            'approved_by_2_name'=>$request->approved_by_2_name,
            'recorded_by_name'=>$request->recorded_by_name,
        ];
        $fp = fopen('settings/capital-expenditure-request.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    public function get_footer_settings(Request $request)
    {
        $string = file_get_contents(public_path("settings/capital-expenditure-request.json"));
        return $string;
    }

    public function get_recipients(Request $request)
    {
        $data['result'] = fractal(Inventory_capital_expenditure_request_recipient::all(), new Inventory_capital_expenditure_request_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
    }

    public function store_recipient(Request $request)
    {
        $this->validate(
            $request,
            [
                'user' => 'required|unique:inventory_capital_expenditure_request_recipient,user_id,NULL,id,deleted_at,NULL',
                'user.email_address' => 'required',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $recipient = new Inventory_capital_expenditure_request_recipient;
            $recipient->user_id = $request->user['id'];
            $recipient->allow_approve = $request->allow_approve && $request->allow_approve == 'true' ? 1 : 0;
            $recipient->notify_email = $request->notify_email && $request->notify_email == 'true' ? 1 : 0;
            $recipient->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($recipient, new Inventory_capital_expenditure_request_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function update_recipient(Request $request,$id)
    {
        $recipient = Inventory_capital_expenditure_request_recipient::findOrFail($id);
        $recipient->allow_approve = $request->allow_approve == 'true' ? 1 : 0;
        $recipient->notify_email = $request->notify_email == 'true' ? 1 : 0;
        $recipient->save();
        return fractal($recipient, new Inventory_capital_expenditure_request_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function destroy_recipient($id)
    {
        $recipient = Inventory_capital_expenditure_request_recipient::findOrFail($id);
        $recipient->delete();
    }

    public function mail_user(Request $request,$uuid,$user_id)
    {   
        $file_name = $request->generated_form['capital_expenditure_request_number_formatted'].'-capital-expenditure-request-'.Carbon::parse($request->generated_form['capital_expenditure_request_date'])->format('Y-m-d').'-'.$request->generated_form['uuid'].'.pdf';
        $mailer = new MailNotification;
        $mailer->send_to_address = $request->user['user']['email_address'];
        $mailer->send_to_name = $request->user['user']['name'];
        $mailer->subject = $request->form_type . " No." . $request->generated_form['capital_expenditure_request_number_formatted'];
        $mailer->form_type = $request->form_type;
        $mailer->form_number = $request->generated_form['capital_expenditure_request_number_formatted'];
        $mailer->attachment_path = $request->generated_form['form'];
        $mailer->attachment_filename = $file_name;
        $mailer->form_approval_url = route('inventory.capital-expenditure-request.email-approve').'?form='.urlencode($request->generated_form['form']).'&uuid='.$request->generated_form['uuid'].'&code='.urlencode(bcrypt($request->generated_form['uuid']));
        $mailer->can_approve = true;
        if(App::environment('local')){
            dd($mailer);
        }else{
            return $mailer->send();
        }
    }

    public function email_approve(Request $request)
    {
        $uuid = $request->uuid;
        $form = $request->form;
        $capital_expenditure_request = Inventory_capital_expenditure_request::where('uuid',$uuid)->first();
        if($capital_expenditure_request){
            if($capital_expenditure_request['is_approved']==1){
                return abort('404');
            }
        }else{
            return abort('404');
        }
        return view('inventory.capital-expenditure-request-email-approve',compact('form','capital_expenditure_request'));
    }
}
