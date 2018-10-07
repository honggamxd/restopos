<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_table_customer;
use App\Restaurant_table;
use App\Restaurant_menu;
use App\Restaurant_order;
use App\Restaurant_order_detail;
use App\Restaurant_temp_bill;
use App\Restaurant_temp_bill_detail;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_accepted_order_cancellation;
use App\Restaurant_order_cancellation;
use App\Restaurant_payment;
use App\Restaurant_meal_types;
use App\Restaurant_invoice_number_log;
use App\User;
use Auth;

class Restaurant_bill_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index(Request $request,$id)
  {
    $data["print"] = $request->print;
    $data["id"] = $id;
    $data['bill_info'] = $this->show_bill($request,$id);
    $data['payment_data'] = app('App\Http\Controllers\Restaurant_payment_controller')->show($request,$id);
    if($data['payment_data']['result']==array()){
      $data['has_payment'] = false;
    }else{
      $data['has_payment'] = true;
    }
    $data['customer_data'] = Restaurant_table_customer::onlyTrashed()->find($data['bill_info']['bill']['restaurant_table_customer_id']);
    $orders = Restaurant_order::where('restaurant_table_customer_id',$data['bill_info']['bill']['restaurant_table_customer_id'])->get()->toArray();
    $data['orders'] = $orders;
    foreach ($orders as $key => $order) {
      $data['orders'][$key]['que_number'] = sprintf('%04d',$order['que_number']);
    }
    // return $data;
    return view('restaurant.bill',$data);
  }

  public function edit(Request $request,$id)
  {
    $data["print"] = $request->print;
    $data["id"] = $id;
    $data['bill_info'] = $this->show_bill($request,$id);
    $data['customer_data'] = Restaurant_table_customer::onlyTrashed()->find($data['bill_info']['bill']['restaurant_table_customer_id']);
    if($data['customer_data']==null){
      return abort(404);
    }
    $data['payment_data'] = app('App\Http\Controllers\Restaurant_payment_controller')->show($request,$id);
    if($data['payment_data']['result']==array()){
      $data['has_payment'] = false;
    }else{
      $data['has_payment'] = true;
    }
    foreach ($data['payment_data']['result'] as $key => $payment_data) {
      $data['payment_data']['result'][$key]['settlement_array'] = [
        'label' => settlements($payment_data['settlement_code']),
        'value' => $payment_data['settlement_code'],
      ];
    }
    $settlements = explode(',', DB::table('app_config')->first()->settlements);
    $settlements_dropdown = array();
    foreach ($settlements as $settlement) {
      $settlements_dropdown[] = [
        'label' => settlements($settlement),
        'value' => $settlement,
      ];
    }
    $data['settlements'] = $settlements_dropdown;
    $restaurant_id = $data['bill_info']['bill']['restaurant_id'];
    $data['menu'] = Restaurant_menu::where('restaurant_id',$restaurant_id)->orderBy('name')->get();
    // return $data;
    return view('restaurant.edit-bill',$data);
  }

  public function update(Request $request)
  {
    DB::beginTransaction();
    try{
      $bill = Restaurant_bill::find($request->bill['id']);
      $bill->excess = $request->bill['excess'];
      $bill->discounts = $request->bill['discounts'];
      $bill->room_service_charge = $request->bill['room_service_charge'];
      $bill->gross_billing = $request->bill['gross_billing'];
      $bill->sc_pwd = $request->bill['sc_pwd'];
      $bill->sc_pwd_discount = $request->bill['sc_pwd_discount'];
      $bill->sc_pwd_vat_exemption = $request->bill['sc_pwd_vat_exemption'];
      $bill->total_discount = $request->bill['total_discount'];
      $bill->net_billing = $request->bill['net_billing'];
      $bill->sales_net_of_vat_and_service_charge = $request->bill['sales_net_of_vat_and_service_charge'];
      $bill->service_charge = $request->bill['service_charge'];
      $bill->vatable_sales = $request->bill['vatable_sales'];
      $bill->output_vat = $request->bill['output_vat'];
      $bill->sales_inclusive_of_vat = $request->bill['sales_inclusive_of_vat'];
      $bill->total_item_amount = $request->bill['total_item_amount'];
      $bill->pax = $request->bill['pax'];
      $bill->guest_name = $request->bill['guest_name'];
      $bill->save();

      foreach ($request->bill_detail as $form_bill_detail) {
        $menu = Restaurant_menu::find($form_bill_detail['restaurant_menu_id']);
        $bill_detail = Restaurant_bill_detail::find($form_bill_detail['id']);
        $bill_detail->restaurant_menu_id = $form_bill_detail['restaurant_menu_id'];
        $bill_detail->quantity = $form_bill_detail['quantity'];
        $bill_detail->restaurant_menu_name = $menu->name;
        $bill_detail->price = $form_bill_detail['price'];
        $bill_detail->save();
      }

      $formdata_payment_id = array();
      foreach ($request->payments as $form_payments) {
        $formdata_payment_id[] = $form_payments['id'];
      }
      $payment_ids = Restaurant_payment::where('restaurant_bill_id',$request->bill['id'])->pluck('id')->toArray();
      foreach ($payment_ids as $payment_id) {
        if(in_array($payment_id, $formdata_payment_id)){
          
        }else{
          Restaurant_payment::find($payment_id)->delete();
        }
      }
      foreach ($request->payments as $form_payments) {
        $formdata_payment_id[] = $form_payments['id'];
        $payments = Restaurant_payment::find($form_payments['id']);
        if($payments){
          $payments->payment = $form_payments['payment'];
          $payments->settlement = $form_payments['settlement_array']['value'];
          $payments->save();
        }else{
          $restaurant_payment = new Restaurant_payment;
          $restaurant_payment->payment = $form_payments['payment'];
          $restaurant_payment->settlement = $form_payments['settlement_array']['value'];
          $restaurant_payment->date_ = strtotime(date("m/d/Y"));
          $restaurant_payment->date_time = strtotime(date("m/d/Y h:i:s A"));
          $restaurant_payment->restaurant_bill_id = $request->bill['id'];
          $restaurant_payment->save();
        }
      }
      
      DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    


  }

  public function bills_test(Request $request,$id)
  {
    
    $data["print"] = $request->print;
    $data["id"] = $id;
    $data['bill_info'] = $this->show_bill($request,$id);
    $data['payment_data'] = app('App\Http\Controllers\Restaurant_payment_controller')->show($request,$id);
    if($data['payment_data']['result']==array()){
      $data['has_payment'] = false;
    }else{
      $data['has_payment'] = true;
    }
    foreach ($data['payment_data']['result'] as $value) {
      if($value['settlement']=='Cash'){
        if($data['bill_info']['bill']['excess']==$value['payment']){
          return true;
        }
      }
    }
    return false;
  }

  public function test(Request $request)
  {
    $bills = Restaurant_bill::all();
    foreach ($bills as $bill) {
      if($this->bills_test($request,$bill->id)){
        $bill->excess = 0;
        $bill->save();
      }
    }
  }

  public function make_bill(Request $request,$id)
  {
    $this->validate($request, [
        'items' => 'valid_restaurant_billing|discount_except:sundry,'.$request->customer_data['sc_pwd']
      ],[
        'discount_except' => 'The number of SC/PWD Must be 0 if the items to bill has Sundries',
        'valid_restaurant_billing' => 'The quantity for billing of the items are not valid.'
      ]);
    DB::beginTransaction();
    try{
        $sales_net_of_vat_and_service_charge = $request->gross_billing/1.12*.9;
            $service_charge = $request->gross_billing/1.12*.1;
            $vatable_sales = $sales_net_of_vat_and_service_charge+$service_charge;
            $output_vat = $vatable_sales*.12;
            $sales_inclusive_of_vat = $vatable_sales+$output_vat;
            $sc_pwd_discount = (float)$request->discount['sc_pwd_discount'];
            $sc_pwd_vat_exemption = (float)$request->discount['sc_pwd_vat_exemption'];
            $room_service_charge = (float)$request->discount['room_service_charge'];
            // $net_billing = $sales_inclusive_of_vat-$sc_pwd_discount-$sc_pwd_vat_exemption;
            // return $data;
            // return $request->all();
            $meal_type = "";
            if(Auth::user()->restaurant_id == 1 || Auth::user()->restaurant_id == 2 || Auth::user()->restaurant_id == 3){
              $meal_types = Restaurant_meal_types::where('restaurant_id',Auth::user()->restaurant_id)->get();
              $time_str = date('H:i:s');
              $time_now = strtotime($time_str);
              if(strtotime($meal_types[0]->schedule)<=$time_now&&strtotime($meal_types[1]->schedule)>$time_now){
                $meal_type = $meal_types[0]->type;
              }elseif(strtotime($meal_types[1]->schedule)<=$time_now&&strtotime($meal_types[2]->schedule)>$time_now){
                $meal_type = $meal_types[1]->type;
              }elseif(strtotime($meal_types[2]->schedule)<=$time_now&&strtotime($meal_types[3]->schedule)>$time_now){
                $meal_type = $meal_types[2]->type;
              }else{
                $meal_type = $meal_types[3]->type;
              }
            }


            // return $request->items;
            // exit;
            $restaurant_table_customer = new Restaurant_table_customer;
            $restaurant_table = new Restaurant_table;
            $restaurant_temp_bill = new Restaurant_temp_bill;
            
            $customer_data = $restaurant_table_customer->find($id);
            
            $restaurant_bill = new Restaurant_bill;
            $check_number = $restaurant_bill
              ->where('date_',strtotime(date('m/d/Y')))
              ->where('restaurant_id',Auth::user()->restaurant_id)
              ->orderBy('id','DESC')
              ->value('check_number');

            $restaurant_bill = new Restaurant_bill;
            $restaurant_bill->date_ = strtotime(date("m/d/Y"));
            $restaurant_bill->date_time = strtotime(date("m/d/Y h:i:s A"));
            $restaurant_bill->pax = $request->customer_data['pax'];
            $restaurant_bill->sc_pwd = $request->customer_data['sc_pwd'];
            $restaurant_bill->sales_net_of_vat_and_service_charge = $sales_net_of_vat_and_service_charge;
            $restaurant_bill->service_charge = $service_charge;
            $restaurant_bill->vatable_sales = $vatable_sales;
            $restaurant_bill->output_vat = $output_vat;
            $restaurant_bill->sales_inclusive_of_vat = $sales_inclusive_of_vat;
            $restaurant_bill->sc_pwd_discount = $sc_pwd_discount;
            $restaurant_bill->sc_pwd_vat_exemption = $sc_pwd_vat_exemption;
            $restaurant_bill->total_discount = $request->discount['total'];
            $restaurant_bill->room_service_charge = $request->discount['room_service_charge'];
            $restaurant_bill->gross_billing = $request->gross_billing;
            $restaurant_bill->net_billing = $request->net_billing;
            $restaurant_bill->total_item_amount = $request->total;
            $restaurant_bill->server_id = $customer_data->server_id;
            $restaurant_bill->cashier_id = Auth::user()->id;
            $restaurant_bill->restaurant_table_customer_id = $customer_data->id;
            $restaurant_bill->table_name = $customer_data->table_name;
            $restaurant_bill->guest_name = $customer_data->guest_name;
            $restaurant_bill->restaurant_id = Auth::user()->restaurant_id;
            $restaurant_bill->type = "good_order";
            $restaurant_bill->meal_type = $meal_type;
            $restaurant_bill->check_number = ($check_number==null||$check_number==0?1:++$check_number);
            $restaurant_bill->save();



            $bill_data = $restaurant_bill->orderBy('id','DESC')->first();

            $bill_preview_detail = $request->items;
            foreach ($bill_preview_detail as $preview_data) {
              if(abs($preview_data["quantity_to_bill"]) != 0){
                $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
                $restaurant_temp_bill_detail_data = $restaurant_temp_bill_detail
                  ->where('restaurant_temp_bill_id',$preview_data["restaurant_temp_bill_id"])
                  ->where('restaurant_menu_id',$preview_data["restaurant_menu_id"])
                  ->first();
                $menu_in_bill_detail = Restaurant_bill_detail::where('restaurant_menu_id',$preview_data["restaurant_menu_id"])->where('restaurant_bill_id',$bill_data->id)->first();
                if($menu_in_bill_detail != null){
                  continue;
                }
                $restaurant_bill_detail = new Restaurant_bill_detail;
                $restaurant_bill_detail->restaurant_menu_id = $preview_data["restaurant_menu_id"];
                $restaurant_bill_detail->restaurant_menu_name = $restaurant_temp_bill_detail_data->restaurant_menu_name;
                $restaurant_bill_detail->quantity = abs($preview_data["quantity_to_bill"]);
                $restaurant_bill_detail->price = $preview_data["price"];
                $restaurant_bill_detail->special_instruction = $preview_data["special_instruction"];
                $restaurant_bill_detail->restaurant_bill_id = $bill_data->id;
                $restaurant_bill_detail->restaurant_id = Auth::user()->restaurant_id;
                $restaurant_bill_detail->date_ = strtotime(date("m/d/Y"));
                $restaurant_bill_detail->save();
                $restaurant_temp_bill_detail_data->quantity -= abs($preview_data["quantity_to_bill"]);
                $restaurant_temp_bill_detail_data->save();
              }
            }
            $restaurant_temp_bill = new Restaurant_temp_bill;
            $restaurant_temp_bill_data = $restaurant_temp_bill
              ->where('restaurant_table_customer_id',$id)
              ->first();
            $remaining_balance = $restaurant_temp_bill_detail
              ->select(DB::raw('SUM(quantity*price) as total'))
              ->where('restaurant_temp_bill_id',$restaurant_temp_bill_data->id)
              ->value('total');
            if($remaining_balance==0){
              $customer_data->has_billed_completely = 1;
            }
            $customer_data->has_bill = 1;
            $customer_data->save();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $this->list_bill($request,$id);
    

  }

  public function show_bill(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_menu = new Restaurant_menu;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
    $data["bill"] = $restaurant_bill->findOrFail($id);
    if($data["bill"]!=null){
      $data["bill"]->date_ = date("F d, Y",$data["bill"]->date_);
      $data["bill"]->date_time = date("h:i:s A",$data["bill"]->date_time);
      $data["bill"]->restaurant_name = DB::table('restaurant')->find($data["bill"]->restaurant_id)->name;
      $data["bill"]->cashier_name = DB::table('user')->find($data["bill"]->cashier_id)->name;
      $data["bill"]->server_name = DB::table('restaurant_server')->find($data["bill"]->server_id)->name;
      $data["bill"]->check_number = sprintf('%04d',$data["bill"]->check_number);
      if($data["bill"]->type=="bad_order"){
        $data["bill_detail"] = $restaurant_accepted_order_cancellation->where("restaurant_bill_id",$id)->get();
        foreach ($data["bill_detail"] as $bill_detail_data) {
          $menu = $restaurant_menu->find($bill_detail_data->restaurant_menu_id);
          $bill_detail_data->menu_data = $menu;
          $bill_detail_data->menu = $menu->name;
          $bill_detail_data->category = $restaurant_menu->find($bill_detail_data->restaurant_menu_id)->category;
          $bill_detail_data->settlement = settlements($bill_detail_data->settlement);
        }
      }else{
        $data["bill_detail"] = $restaurant_bill_detail->where("restaurant_bill_id",$id)->get();
        foreach ($data["bill_detail"] as $bill_detail_data) {
          $menu = $restaurant_menu->find($bill_detail_data->restaurant_menu_id);
          $bill_detail_data->menu_data = $menu;
          $bill_detail_data->menu = $menu->name;
          $bill_detail_data->category = $restaurant_menu->find($bill_detail_data->restaurant_menu_id)->category;
        }
      }
      return $data;
    }
  }
  public function list_bill(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $data["result"] = $restaurant_bill->where("restaurant_table_customer_id",$id)->get();
    $has_unpaid_order = false;
    foreach ($data["result"] as $bill_data) {
      $data["table_name"] = $bill_data->table_name;
      $bill_data->total = $restaurant_bill_detail->select(DB::raw('SUM(price*quantity) as total'))->where("restaurant_bill_id",$bill_data->id)->first()->total;
      if($bill_data->is_paid == 0){
        $has_unpaid_order = true;
      }
      $bill_data->check_number = sprintf('%04d',$bill_data->check_number);
    }
    $data["has_paid"] = ($has_unpaid_order?0:1);
    return $data;
  }

  public function delete(Request $request,$id)
  {
    // return $request->all();
    $this->validate($request, [
        'bill_data.id' => 'exists:restaurant_bill,id,deleted_at,NULL'
      ],[
        'bill_data.id.exists' => 'The Check number is not valid.'
      ]);
    DB::beginTransaction();
    try{
        $restaurant_bill_data = Restaurant_bill::find($id);
        // return $restaurant_bill_data->customer;
        $restaurant_bill_data->deleted_comment = $request->deleted_comment;
        $restaurant_bill_data->deleted_by = Auth::user()->id;
        $restaurant_table_customer = new Restaurant_table_customer;
        $customer_data = $restaurant_table_customer->withTrashed()->find($restaurant_bill_data->restaurant_table_customer_id);
        $restaurant_bill_detail_data = Restaurant_bill_detail::where('restaurant_bill_id',$id);
        Restaurant_payment::where('restaurant_bill_id',$id)->delete();
        if($customer_data->deleted_at==null){
          $restaurant_bill_data->is_paid = 0;
          $customer_data->has_billed_completely = 0;
          if($restaurant_bill_data->type=="good_order"){
            $customer_data->has_paid = 0;
            foreach ($restaurant_bill_detail_data->get() as $restaurant_bill_items) {
              $restaurant_temp_bill = new Restaurant_temp_bill;
              $temp_bill_item_data = $restaurant_temp_bill
              ->join('restaurant_temp_bill_detail','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
              ->where('restaurant_temp_bill.restaurant_table_customer_id',$restaurant_bill_data->restaurant_table_customer_id)
              ->where('restaurant_temp_bill_detail.restaurant_menu_id',$restaurant_bill_items->restaurant_menu_id)
              ->first();
              $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
              $restaurant_temp_bill_detail_data = $restaurant_temp_bill_detail->find($temp_bill_item_data->id);
              $restaurant_temp_bill_detail_data->quantity += $restaurant_bill_items->quantity;
              $restaurant_temp_bill_detail_data->save();
            }
          }else{
            $customer_data->has_paid = 1;
            $customer_data->cancellation_order_status = 1;
            $restaurant_accepted_order_cancellation_data = Restaurant_accepted_order_cancellation::where('restaurant_bill_id',$restaurant_bill_data->id)
            ->update([
              'settlement' => '',
              'has_settled' => 0
              ]);
          }
          $customer_data->save();
        }
        $restaurant_bill_data->save();
        $restaurant_bill_data->delete();
        $restaurant_bill_detail_data->delete();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
  }

  public function update_invoice(Request $request,$id)
  {
    try{
      $restaurant_bill_data = Restaurant_bill::find($id);
      $restaurant_bill_data->invoice_number = $request->invoice_number;
      $restaurant_bill_data->save();

      $invoice_number_log = new Restaurant_invoice_number_log;
      $invoice_number_log->invoice_number = $request->invoice_number;
      $invoice_number_log->user_id = Auth::user()->id;
      $invoice_number_log->restaurant_bill_id = $id;
      $invoice_number_log->save();
      DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
  }

  public function show_invoice_number_logs(Request $request,$id)
  {
    $logs = Restaurant_invoice_number_log::where('restaurant_bill_id',$id)->get();
    foreach ($logs as $log) {
      $log->user_data =  User::withTrashed()->find($log->user_id);
    }
    return $logs;
  }

}
