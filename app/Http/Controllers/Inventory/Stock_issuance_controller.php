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

use App\Inventory\Inventory_stock_issuance;
use App\Inventory\Inventory_stock_issuance_detail;
use App\Inventory\Inventory_stock_issuance_recipient;
use App\Inventory\Inventory_item;

use App\Inventory\Inventory_item_detail;
use App\User;
use App\Transformers\User_transformer;

use App\Transformers\Inventory_item_transformer;
use App\Transformers\Inventory_stock_issuance_transformer;

use App\Transformers\Inventory_stock_issuance_recipient_transformer;
use App\Helpers\MailNotification;

class Stock_issuance_controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->check_json_settings();
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
                // 'stock_issuance_number' => 'required|numeric|unique:inventory_stock_issuance,stock_issuance_number,NULL,id,deleted_at,NULL',
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
            // $stock_issuance->stock_issuance_number = $request->stock_issuance_number;
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
        $data['stock_issuance'] = fractal($stock_issuance, new Inventory_stock_issuance_transformer)->toArray();
        $recipients = Inventory_stock_issuance_recipient::query();
        $recipients->leftJoin('user','inventory_stock_issuance_recipient.user_id','=','user.id');
        $recipients->select('inventory_stock_issuance_recipient.*');
        $recipients->whereNotNull('user.email_address');
        $recipients->where('inventory_stock_issuance_recipient.notify_email','1');
        $recipients = $recipients->get();
        $data['recipients'] = fractal($recipients, new Inventory_stock_issuance_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
        # code...
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'stock_issuance_number' => 'required|numeric|unique:inventory_stock_issuance,stock_issuance_number,'.$id.',id,deleted_at,NULL',
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
        $validator->after(function ($validator) use ($request,$id){
            $stock_issuance = Inventory_stock_issuance::findOrFail($id);
            if($request->approved_by_name != null && $stock_issuance->is_approved==0){
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
            // $stock_issuance->stock_issuance_number = $request->stock_issuance_number;
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
            $stock_issuance->is_approved = $request->approved_by_date != null ? 1 : 0;
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
                    if($stock_issuance->is_approved != null){
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
        return fractal($stock_issuance, new Inventory_stock_issuance_transformer)->parseIncludes('details.inventory_item')->toArray();
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

    public function approve($id)
    {
        $validator = Validator::make(
            [],
            []
        );
        $validator->after(function ($validator) use ($id){
            $stock_issuance = Inventory_stock_issuance::findOrFail($id);
            if($stock_issuance->is_approved==1){
                $validator->errors()->add('error', 'The purchase request that you are going to approve is already approved by  '.$stock_issuance->approved_by_name.' on '.Carbon::parse($stock_issuance->approved_by_date)->format('F d, Y') );
            }else{
                $stock_issuance = Inventory_stock_issuance::findOrFail($id);
                foreach ($stock_issuance->details as $form_item) {
                    $item = fractal(Inventory_item::find($form_item->inventory_item_id), new Inventory_item_transformer)->toArray();
                    if($item['total_quantity']<$form_item->quantity){
                        $validator->errors()->add('error', 'Items');
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
            $stock_issuance->approved_by_name = Auth::user()->name;
            $stock_issuance->approved_by_date = Carbon::now()->format('Y-m-d');
            $stock_issuance->is_approved = 1;
            $stock_issuance->save();
            $stock_issuance_items = Inventory_stock_issuance_detail::where('inventory_stock_issuance_id',$id)->get();
            foreach ($stock_issuance_items as $form_item) {
                $item_detail = Inventory_item_detail::where('inventory_item_id',$form_item->inventory_item_id)->where('inventory_stock_issuance_id',$id)->first();
                if($item_detail == null){
                    if($stock_issuance->is_approved != null){
                        $stock_issuance_detail = Inventory_stock_issuance_detail::find($form_item->id);
                        $item_detail = new Inventory_item_detail;
                        $item_detail->inventory_item_id = $stock_issuance_detail->inventory_item_id;
                        $item_detail->unit_cost = $form_item->unit_price;
                        $item_detail->quantity = $form_item->quantity * -1;
                        $item_detail->inventory_stock_issuance_id = $stock_issuance->id;
                        $item_detail->save();
                    }
                }
            }
            
            // DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($stock_issuance, new Inventory_stock_issuance_transformer)->parseIncludes('details.inventory_item')->toArray();
    }

    public function settings(Request $request)
    {
        $user = new User;

        $data['users'] = fractal(User::all(), new User_transformer)->parseIncludes('restaurant')->toArray();
        return view('inventory.stock-issuance-settings',$data);
    }

    private function check_json_settings()
    {
        if(!file_exists(public_path('settings/stock-issuance.json'))){
            $data = array();
            $data['footer'] = ['noted_by_name'=>[]];
            $fp = fopen('settings/stock-issuance.json', 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }

    public function update_footer_settings(Request $request)
    {
        $data = array();
        $data['footer'] = ['noted_by_name'=>$request->noted_by_name];
        $fp = fopen('settings/stock-issuance.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    public function get_footer_settings(Request $request)
    {
        $string = file_get_contents(public_path("settings/stock-issuance.json"));
        return $string;
    }

    public function get_recipients(Request $request)
    {
        $data['result'] = fractal(Inventory_stock_issuance_recipient::all(), new Inventory_stock_issuance_recipient_transformer)->parseIncludes('user')->toArray();
        return $data;
    }

    public function store_recipient(Request $request)
    {
        $this->validate(
            $request,
            [
                'user' => 'required|unique:inventory_stock_issuance_recipient,user_id,NULL,id,deleted_at,NULL',
                'user.email_address' => 'required',
            ],
            [
                'requested_by_name.required_with' => 'Required if the name or date is filled.',
            ]
        );
        DB::beginTransaction();
        try{
            $recipient = new Inventory_stock_issuance_recipient;
            $recipient->user_id = $request->user['id'];
            $recipient->allow_approve = $request->allow_approve && $request->allow_approve == 'true' ? 1 : 0;
            $recipient->notify_email = $request->notify_email && $request->notify_email == 'true' ? 1 : 0;
            $recipient->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return fractal($recipient, new Inventory_stock_issuance_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function update_recipient(Request $request,$id)
    {
        $recipient = Inventory_stock_issuance_recipient::findOrFail($id);
        $recipient->allow_approve = $request->allow_approve == 'true' ? 1 : 0;
        $recipient->notify_email = $request->notify_email == 'true' ? 1 : 0;
        $recipient->save();
        return fractal($recipient, new Inventory_stock_issuance_recipient_transformer)->parseIncludes('user.restaurant')->toArray();
    }

    public function destroy_recipient($id)
    {
        $recipient = Inventory_stock_issuance_recipient::findOrFail($id);
        $recipient->delete();
    }

    public function mail_user(Request $request,$uuid,$user_id)
    {   
        $file_name = $request->generated_form['stock_issuance_number_formatted'].'-stock-issuance-'.Carbon::parse($request->generated_form['stock_issuance_date'])->format('Y-m-d').'-'.$request->generated_form['uuid'].'.pdf';
        $mailer = new MailNotification;
        $mailer->send_to_address = $request->user['user']['email_address'];
        $mailer->send_to_name = $request->user['user']['name'];
        $mailer->subject = $request->form_type . " No." . $request->generated_form['stock_issuance_number_formatted'];
        $mailer->form_type = $request->form_type;
        $mailer->form_number = $request->generated_form['stock_issuance_number_formatted'];
        $mailer->attachment_path = $request->generated_form['form'];
        $mailer->attachment_filename = $file_name;
        $mailer->form_approval_url = route('inventory.stock-issuance.email-approve').'?form='.urlencode($request->generated_form['form']).'&uuid='.$request->generated_form['uuid'].'&code='.urlencode(bcrypt($request->generated_form['uuid']));
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
        $stock_issuance = Inventory_stock_issuance::where('uuid',$uuid)->first();
        if($stock_issuance){
            if($stock_issuance['is_approved']==1){
                return abort('404');
            }
        }else{
            return abort('404');
        }
        return view('inventory.stock-issuance-email-approve',compact('form','stock_issuance'));
    }
}
