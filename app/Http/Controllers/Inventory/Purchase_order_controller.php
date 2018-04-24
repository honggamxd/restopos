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

use App\Inventory\Inventory_purchase_order;
use App\Inventory\Inventory_purchase_order_detail;
use App\Inventory\Inventory_purchase_order_recipient;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;
use App\User;
use App\Transformers\User_transformer;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_purchase_order_transformer;
use App\Transformers\Inventory_purchase_order_recipient_transformer;
use App\Helpers\MailNotification;

class Purchase_order_controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
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
        $form_number = Inventory_purchase_order::orderBy('purchase_order_number','DESC')->first();
        $data['form_number'] = $form_number ? ++$form_number->purchase_order_number : 1;
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
                // 'purchase_order_number' => 'required|numeric|unique:inventory_purchase_order,purchase_order_number,NULL,id,deleted_at,NULL',
                'purchase_order_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
                'requested_by_name' => 'required_with:requested_by_name,requested_by_date',
                'requested_by_date' => 'required_with:requested_by_name,requested_by_date',
                'noted_by_name' => 'required_with:noted_by_name,noted_by_date',
                'noted_by_date' => 'required_with:noted_by_name,noted_by_date',
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
            $purchase_order = new Inventory_purchase_order;
            // $purchase_order->purchase_order_number = $request->purchase_order_number;
            $purchase_order->purchase_order_date = Carbon::parse($request->purchase_order_date);
            $purchase_order->supplier_name = $request->supplier_name;
            $purchase_order->supplier_address = $request->supplier_address;
            $purchase_order->supplier_tin = $request->supplier_tin;
            $purchase_order->term = $request->term;
            $purchase_order->requesting_department = $request->requesting_department;
            $purchase_order->purpose = $request->purpose;
            $purchase_order->request_chargeable_to = $request->request_chargeable_to;
            $purchase_order->requested_by_name = $request->requested_by_name;
            $purchase_order->requested_by_date = $request->requested_by_date!=null ? Carbon::parse($request->requested_by_date) : null;
            $purchase_order->noted_by_name = $request->noted_by_name;
            $purchase_order->noted_by_date = $request->noted_by_date!=null ? Carbon::parse($request->noted_by_date) : null;
            $purchase_order->approved_by_name = $request->approved_by_name;
            $purchase_order->approved_by_date = $request->approved_by_date!=null ? Carbon::parse($request->approved_by_date) : null;
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
        $data['purchase_order'] = fractal($purchase_order, new Inventory_purchase_order_transformer)->toArray();
        $recipients = Inventory_purchase_order_recipient::query();
        $recipients->leftJoin('user','inventory_purchase_order_recipient.user_id','=','user.id');
        $recipients->select('inventory_purchase_order_recipient.*');
        $recipients->whereNotNull('user.email_address');
        $recipients->where('inventory_purchase_order_recipient.notify_email','1');
        $recipients = $recipients->get();
        $data['recipients'] = fractal($recipients, new Inventory_purchase_order_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
        # code...
    }

    public function update(Request $request,$id)
    {
        $this->validate(
            $request,
            [
                // 'purchase_order_number' => 'required|numeric|unique:inventory_purchase_order,purchase_order_number,'.$id.',id,deleted_at,NULL',
                'purchase_order_date' => 'required|date',
                'supplier_name' => 'required',
                // 'supplier_address' => 'required',
                // 'supplier_tin' => 'required',
                'term' => 'required',
                'requesting_department' => 'required',
                // 'purpose' => 'required',
                // 'request_chargeable_to' => 'required',
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
            $purchase_order = Inventory_purchase_order::findOrFail($id);
            // $purchase_order->purchase_order_number = $request->purchase_order_number;
            $purchase_order->purchase_order_date = Carbon::parse($request->purchase_order_date);
            $purchase_order->supplier_name = $request->supplier_name;
            $purchase_order->supplier_address = $request->supplier_address;
            $purchase_order->supplier_tin = $request->supplier_tin;
            $purchase_order->term = $request->term;
            $purchase_order->requesting_department = $request->requesting_department;
            $purchase_order->purpose = $request->purpose;
            $purchase_order->request_chargeable_to = $request->request_chargeable_to;
            $purchase_order->requested_by_name = $request->requested_by_name;
            $purchase_order->requested_by_date = $request->requested_by_date!=null ? Carbon::parse($request->requested_by_date) : null;
            $purchase_order->noted_by_name = $request->noted_by_name;
            $purchase_order->noted_by_date = $request->noted_by_date!=null ? Carbon::parse($request->noted_by_date) : null;
            $purchase_order->approved_by_name = $request->approved_by_name;
            $purchase_order->approved_by_date = $request->approved_by_date!=null ? Carbon::parse($request->approved_by_date) : null;
            $purchase_order->is_approved = $request->approved_by_date != null ? 1 : 0;
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
            $purchase_order = Inventory_purchase_order::findOrFail($id);
            if($purchase_order->is_approved==1){
                $validator->errors()->add('error', 'The purchase order that you are going to approve is already approved by  '.$purchase_order->approved_by_name.' on '.Carbon::parse($purchase_order->approved_by_date)->format('F d, Y') );
            }
        });
        if($validator->fails()){
            return response($validator->errors(), 422);
        }
        DB::beginTransaction();
        try{
            $purchase_order = Inventory_purchase_order::findOrFail($id);
            $purchase_order->approved_by_name = Auth::user()->name;
            $purchase_order->approved_by_date = Carbon::now()->format('Y-m-d');
            $purchase_order->is_approved = 1;
            $purchase_order->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($purchase_order, new Inventory_purchase_order_transformer)->parseIncludes('details.inventory_item')->toArray();
    }

    public function settings(Request $request)
    {
        $user = new User;
        $this->check_json_settings();

        $data['users'] = fractal(User::all(), new User_transformer)->parseIncludes('restaurant')->toArray();
        return view('inventory.purchase-order-settings',$data);
    }

    private function check_json_settings()
    {
        if(!file_exists(public_path('settings/purchase-order.json'))){
            $data = array();
            $data['footer'] = ['noted_by_name'=>[]];
            $fp = fopen('settings/purchase-order.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }

    public function update_footer_settings(Request $request)
    {
        $data = array();
        $data['footer'] = ['noted_by_name'=>$request->noted_by_name];
        $fp = fopen('settings/purchase-order.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    public function get_footer_settings(Request $request)
    {
        $string = file_get_contents(public_path("settings/purchase-order.json"));
        return $string;
    }

    public function get_recipients(Request $request)
    {
        $data['result'] = fractal(Inventory_purchase_order_recipient::all(), new Inventory_purchase_order_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
    }

    public function store_recipient(Request $request)
    {
        $this->validate(
            $request,
            [
                'user' => 'required|unique:inventory_purchase_order_recipient,user_id,NULL,id,deleted_at,NULL',
                'user.email_address' => 'required',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $recipient = new Inventory_purchase_order_recipient;
            $recipient->user_id = $request->user['id'];
            $recipient->allow_approve = $request->allow_approve && $request->allow_approve == 'true' ? 1 : 0;
            $recipient->notify_email = $request->notify_email && $request->notify_email == 'true' ? 1 : 0;
            $recipient->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($recipient, new Inventory_purchase_order_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function update_recipient(Request $request,$id)
    {
        $recipient = Inventory_purchase_order_recipient::findOrFail($id);
        $recipient->allow_approve = $request->allow_approve == 'true' ? 1 : 0;
        $recipient->notify_email = $request->notify_email == 'true' ? 1 : 0;
        $recipient->save();
        return fractal($recipient, new Inventory_purchase_order_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function destroy_recipient($id)
    {
        $recipient = Inventory_purchase_order_recipient::findOrFail($id);
        $recipient->delete();
    }

    public function mail_user(Request $request,$uuid,$user_id)
    {   
        $file_name = $request->generated_form['purchase_order_number_formatted'].'-purchase-order-'.Carbon::parse($request->generated_form['purchase_order_date'])->format('Y-m-d').'-'.$request->generated_form['uuid'].'.pdf';
        $mailer = new MailNotification;
        $mailer->send_to_address = $request->user['user']['email_address'];
        $mailer->send_to_name = $request->user['user']['name'];
        $mailer->subject = $request->form_type . " No." . $request->generated_form['purchase_order_number_formatted'];
        $mailer->form_type = $request->form_type;
        $mailer->form_number = $request->generated_form['purchase_order_number_formatted'];
        $mailer->attachment_path = $request->generated_form['form'];
        $mailer->attachment_filename = $file_name;
        $mailer->form_approval_url = route('inventory.purchase-order.email-approve').'?form='.urlencode($request->generated_form['form']).'&uuid='.$request->generated_form['uuid'].'&code='.urlencode(bcrypt($request->generated_form['uuid']));
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
        $purchase_order = Inventory_purchase_order::where('uuid',$uuid)->first();
        if($purchase_order){
            if($purchase_order['is_approved']==1){
                return abort('404');
            }
        }else{
            return abort('404');
        }
        return view('inventory.purchase-order-email-approve',compact('form','purchase_order'));
    }
}
