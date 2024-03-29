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
use App;


use App\Inventory\Inventory_purchase_request;
use App\Inventory\Inventory_purchase_request_detail;
use App\Inventory\Inventory_purchase_request_recipient;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;
use App\User;
use App\Transformers\User_transformer;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_purchase_request_transformer;
use App\Transformers\Inventory_purchase_request_recipient_transformer;
use App\Helpers\MailNotification;

class Purchase_request_controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->check_json_settings();
    }

    public function index(Request $request,$uuid)
    {
        $data = Inventory_purchase_request::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_purchase_request_transformer)->parseIncludes('details.inventory_item')->toArray();
        $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
        $pdf->setPaper('legal', 'portrait');
        $pdf->loadView('pdf.purchase-request', $data);
        // return $data;
        // return view('pdf.purchase-request');
        return $pdf->stream($data['purchase_request_number_formatted'].'-purchase-request-'.Carbon::parse($data['purchase_request_date'])->format('Y-m-d').'-'.$data['uuid'].'.pdf');
    }

    public function show_list(Request $request)
    {
        return view('inventory.purchase-request-list');
    }

    public function get_list(Request $request)
    {
        $result = Inventory_purchase_request::query();
        if($request->searchString!=null&&trim($request->searchString)!=""){
        $result->where(function ($query) use ($request){
            $query->orWhere('purchase_request_number','LIKE',"%".(integer)$request->searchString."%");
            
            });
        }
        if($request->autocomplete){
            if($request->approved=='1'){
                $result->whereNotNull('approved_by_name');
                $result->whereNotNull('approved_by_date');
            }else{
                $result->whereNull('approved_by_name');
                $result->whereNull('approved_by_date');
            }
            
            if($request->capex=='1'){
                $result->where('type_of_item_requested','capex');
            }
        }
        $number_of_pages = $request->autocomplete ? 10 : 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_purchase_request_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function create(Request $request)
    {
        $data['edit_mode'] = 'create';
        $form_number = Inventory_purchase_request::orderBy('purchase_request_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->purchase_request_number : 1;
        return view('inventory.purchase-request-form',$data);
    }
    
    public function edit(Request $request,$uuid)
    {
        $data = Inventory_purchase_request::where('uuid',$uuid)->first();
        if($data==null){
            return abort('404');
        }
        $data = fractal($data, new Inventory_purchase_request_transformer)->parseIncludes('details.inventory_item')->toArray();
        $data['data'] = $data;
        $data['edit_mode'] = 'update';
        return view('inventory.purchase-request-form',$data);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                // 'purchase_request_number' => 'required|numeric|unique:inventory_purchase_request,purchase_request_number,NULL,id,deleted_at,NULL',
                'purchase_request_date' => 'required|date',
                'requesting_department' => 'required',
                'reason_for_the_request' => 'required',
                'request_chargeable_to' => 'required',
                'type_of_item_requested' => 'required',
                'date_needed' => 'required|date',
                'requested_by_name' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_date' => 'required_with:requested_by_name,requested_by_date',
                'noted_by_name' => 'required_with:noted_by_name,noted_by_date',
                'noted_by_date' => 'required_with:noted_by_name,noted_by_date',
                'approved_by_name' => 'required_with:approved_by_name,approved_by_date',
                'approved_by_date' => 'required_with:approved_by_name,approved_by_date',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
                'requested_by_date.required_with' => 'Required if the name or date is filled.',
                'noted_by_name.required_with' => 'Required if the name or date is filled.',
                'noted_by_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $purchase_request = new Inventory_purchase_request;
            // $purchase_request->purchase_request_number = $request->purchase_request_number;
            $purchase_request->purchase_request_date = Carbon::parse($request->purchase_request_date);
            $purchase_request->requesting_department = $request->requesting_department;
            $purchase_request->reason_for_the_request = $request->reason_for_the_request;
            $purchase_request->request_chargeable_to = $request->request_chargeable_to;
            $purchase_request->type_of_item_requested = $request->type_of_item_requested;
            $purchase_request->date_needed = $request->date_needed != null ? Carbon::parse($request->date_needed) : null;
            $purchase_request->requested_by_name = $request->requested_by_name;
            $purchase_request->requested_by_date = $request->requested_by_date != null ? Carbon::parse($request->requested_by_date) : null;
            $purchase_request->noted_by_name = $request->noted_by_name;
            $purchase_request->noted_by_date = $request->noted_by_date != null ? Carbon::parse($request->noted_by_date) : null;
            // $purchase_request->approved_by_name = $request->approved_by_name;
            // $purchase_request->approved_by_date = $request->approved_by_date != null ? Carbon::parse($request->approved_by_date) : null;
            $purchase_request->save();

            $purchase_request = Inventory_purchase_request::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $purchase_request_detail = new Inventory_purchase_request_detail;
                $item = fractal(Inventory_item::find($form_item['id']), new Inventory_item_transformer)->toArray();
                $purchase_request_detail->inventory_purchase_request_id = $purchase_request->id;
                $purchase_request_detail->balance_on_hand = $item['total_quantity'];
                $purchase_request_detail->inventory_item_id = $form_item['id'];
                $purchase_request_detail->unit_price = $form_item['unit_cost'];
                $purchase_request_detail->quantity = $form_item['quantity'];
                $purchase_request_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        $data['purchase_request'] = fractal($purchase_request, new Inventory_purchase_request_transformer)->toArray();
        $recipients = Inventory_purchase_request_recipient::query();
        $recipients->leftJoin('user','inventory_purchase_request_recipient.user_id','=','user.id');
        $recipients->select('inventory_purchase_request_recipient.*');
        $recipients->whereNotNull('user.email_address');
        $recipients->where('inventory_purchase_request_recipient.notify_email','1');
        $recipients = $recipients->get();
        $data['recipients'] = fractal($recipients, new Inventory_purchase_request_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
        # code...
    }

    public function update(Request $request,$id)
    {
        $this->validate(
            $request,
            [
                // 'purchase_request_number' => 'required|numeric|unique:inventory_purchase_request,purchase_request_number,'.$id.',id,deleted_at,NULL',
                'purchase_request_date' => 'required|date',
                'requesting_department' => 'required',
                'reason_for_the_request' => 'required',
                'request_chargeable_to' => 'required',
                'type_of_item_requested' => 'required',
                'date_needed' => 'required|date',
                'requested_by_name' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_date' => 'required_with:requested_by_name,requested_by_date',
                'noted_by_name' => 'required_with:noted_by_name,noted_by_date',
                'noted_by_date' => 'required_with:noted_by_name,noted_by_date',
                'approved_by_name' => 'required_with:approved_by_name,approved_by_date',
                'approved_by_date' => 'required_with:approved_by_name,approved_by_date',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
                'requested_by_date.required_with' => 'Required if the name or date is filled.',
                'noted_by_name.required_with' => 'Required if the name or date is filled.',
                'noted_by_date.required_with' => 'Required if the name or date is filled.',
                'approved_by_name.required_with' => 'Required if the name or date is filled.',
                'approved_by_date.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            // return $request->all();
            $purchase_request = Inventory_purchase_request::findOrFail($id);
            // $purchase_request->purchase_request_number = $request->purchase_request_number;
            $purchase_request->purchase_request_date = Carbon::parse($request->purchase_request_date);
            $purchase_request->requesting_department = $request->requesting_department;
            $purchase_request->reason_for_the_request = $request->reason_for_the_request;
            $purchase_request->request_chargeable_to = $request->request_chargeable_to;
            $purchase_request->type_of_item_requested = $request->type_of_item_requested;
            $purchase_request->date_needed = $request->date_needed != null ? Carbon::parse($request->date_needed) : null;
            $purchase_request->requested_by_name = $request->requested_by_name;
            $purchase_request->requested_by_date = $request->requested_by_date != null ? Carbon::parse($request->requested_by_date) : null;
            $purchase_request->noted_by_name = $request->noted_by_name;
            $purchase_request->noted_by_date = $request->noted_by_date != null ? Carbon::parse($request->noted_by_date) : null;
            $purchase_request->approved_by_name = $request->approved_by_name;
            $purchase_request->approved_by_date = $request->approved_by_date != null ? Carbon::parse($request->approved_by_date) : null;
            $purchase_request->is_approved = $request->approved_by_date != null ? 1 : 0;
            $purchase_request->save();

            $purchase_request = Inventory_purchase_request::orderBy('id','DESC')->first();

            foreach ($request->items as $form_item) {
                $purchase_request_detail = Inventory_purchase_request_detail::find($form_item['id']);
                $purchase_request_detail->unit_price = $form_item['unit_cost'];
                $purchase_request_detail->quantity = $form_item['quantity'];
                $purchase_request_detail->save();
            }

            
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($purchase_request, new Inventory_purchase_request_transformer)->toArray();
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $purchase_request = Inventory_purchase_request::findOrFail($id);
            $purchase_request_detail = Inventory_purchase_request_detail::where('inventory_purchase_request_id',$id);
            $purchase_request->delete();
            $purchase_request_detail->delete();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
    }

    
    public function approve($id)
    {
        $validator = Validator::make(
            [],
            []
        );
        $validator->after(function ($validator) use ($id){
            $purchase_request = Inventory_purchase_request::findOrFail($id);
            if($purchase_request->is_approved==1){
                $validator->errors()->add('error', 'The purchase request that you are going to approve is already approved by  '.$purchase_request->approved_by_name.' on '.Carbon::parse($purchase_request->approved_by_date)->format('F d, Y') );
            }
        });
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $purchase_request = Inventory_purchase_request::findOrFail($id);
            $purchase_request->approved_by_name = Auth::user()->name;
            $purchase_request->approved_by_date = Carbon::now()->format('Y-m-d');
            $purchase_request->is_approved = 1;
            $purchase_request->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($purchase_request, new Inventory_purchase_request_transformer)->parseIncludes('details.inventory_item')->toArray();
    }

    public function settings(Request $request)
    {
        $user = new User;

        $data['users'] = fractal(User::all(), new User_transformer)->parseIncludes('restaurant')->toArray();
        return view('inventory.purchase-request-settings',$data);
    }

    private function check_json_settings()
    {
        if(!file_exists(public_path('settings/purchase-request.json'))){
            $data = array();
            $data['footer'] = ['noted_by_name'=>[]];
            $fp = fopen('settings/purchase-request.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }

    public function update_footer_settings(Request $request)
    {
        $data = array();
        $data['footer'] = ['noted_by_name'=>$request->noted_by_name];
        $fp = fopen('settings/purchase-request.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    public function get_footer_settings(Request $request)
    {
        $string = file_get_contents(public_path("settings/purchase-request.json"));
        return $string;
    }

    public function get_recipients(Request $request)
    {
        $data['result'] = fractal(Inventory_purchase_request_recipient::all(), new Inventory_purchase_request_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
    }

    public function store_recipient(Request $request)
    {
        $this->validate(
            $request,
            [
                'user' => 'required|unique:inventory_purchase_request_recipient,user_id,NULL,id,deleted_at,NULL',
                'user.email_address' => 'required',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $recipient = new Inventory_purchase_request_recipient;
            $recipient->user_id = $request->user['id'];
            $recipient->allow_approve = $request->allow_approve && $request->allow_approve == 'true' ? 1 : 0;
            $recipient->notify_email = $request->notify_email && $request->notify_email == 'true' ? 1 : 0;
            $recipient->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($recipient, new Inventory_purchase_request_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function update_recipient(Request $request,$id)
    {
        $recipient = Inventory_purchase_request_recipient::findOrFail($id);
        $recipient->allow_approve = $request->allow_approve == 'true' ? 1 : 0;
        $recipient->notify_email = $request->notify_email == 'true' ? 1 : 0;
        $recipient->save();
        return fractal($recipient, new Inventory_purchase_request_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function destroy_recipient($id)
    {
        $recipient = Inventory_purchase_request_recipient::findOrFail($id);
        $recipient->delete();
    }

    public function mail_user(Request $request,$uuid,$user_id)
    {   
        $file_name = $request->generated_form['purchase_request_number_formatted'].'-purchase-request-'.Carbon::parse($request->generated_form['purchase_request_date'])->format('Y-m-d').'-'.$request->generated_form['uuid'].'.pdf';
        $mailer = new MailNotification;
        $mailer->send_to_address = $request->user['user']['email_address'];
        $mailer->send_to_name = $request->user['user']['name'];
        $mailer->subject = $request->form_type . " No." . $request->generated_form['purchase_request_number_formatted'];
        $mailer->form_type = $request->form_type;
        $mailer->form_number = $request->generated_form['purchase_request_number_formatted'];
        $mailer->attachment_path = $request->generated_form['form'];
        $mailer->attachment_filename = $file_name;
        $mailer->form_approval_url = route('inventory.purchase-request.email-approve').'?form='.urlencode($request->generated_form['form']).'&uuid='.$request->generated_form['uuid'].'&code='.urlencode(bcrypt($request->generated_form['uuid']));
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
        $purchase_request = Inventory_purchase_request::where('uuid',$uuid)->first();
        if($purchase_request){
            if($purchase_request['is_approved']==1){
                return abort('404');
            }
        }else{
            return abort('404');
        }
        return view('inventory.purchase-request-email-approve',compact('form','purchase_request'));
    }
}
