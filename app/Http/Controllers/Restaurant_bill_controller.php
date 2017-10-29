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
class Restaurant_bill_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function index(Request $request,$id)
  {
    $data["print"] = $request->print;
    $data["id"] = $id;
    return view('restaurant.bill',$data);
  }

  public function make_bill(Request $request,$id)
  {
    $sales_net_of_vat_and_service_charge = $request->gross_billing/1.12*.9;
    $service_charge = $request->gross_billing/1.12*.1;
    $vatable_sales = $sales_net_of_vat_and_service_charge+$service_charge;
    $output_vat = $vatable_sales*.12;
    $sales_inclusive_of_vat = $vatable_sales+$output_vat;
    $sc_pwd_discount = (float)$request->discount['sc_pwd_discount'];
    $sc_pwd_vat_exemption = (float)$request->discount['sc_pwd_vat_exemption'];
    // $net_billing = $sales_inclusive_of_vat-$sc_pwd_discount-$sc_pwd_vat_exemption;
    // return $data;
    // return $request->all();
    $this->validate($request, [
        'items' => 'valid_restaurant_billing'
      ],[
        'valid_restaurant_billing' => 'The quantity for billing of the items are not valid.'
      ]);
    // return $request->items;
    // exit;
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table = new Restaurant_table;
    $restaurant_temp_bill = new Restaurant_temp_bill;
    
    $customer_data = $restaurant_table_customer->find($id);
    
    $restaurant_bill = new Restaurant_bill;
    $check_number = $restaurant_bill
      ->where('date_',strtotime(date('m/d/Y')))
      ->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)
      ->where('type','good_order')
      ->orderBy('id','DESC')
      ->value('check_number');

    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill->date_ = strtotime(date("m/d/Y"));
    $restaurant_bill->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_bill->pax = $customer_data->pax;
    $restaurant_bill->sc_pwd = $customer_data->sc_pwd;
    $restaurant_bill->sales_net_of_vat_and_service_charge = $sales_net_of_vat_and_service_charge;
    $restaurant_bill->service_charge = $service_charge;
    $restaurant_bill->vatable_sales = $vatable_sales;
    $restaurant_bill->output_vat = $output_vat;
    $restaurant_bill->sales_inclusive_of_vat = $sales_inclusive_of_vat;
    $restaurant_bill->sc_pwd_discount = $sc_pwd_discount;
    $restaurant_bill->sc_pwd_vat_exemption = $sc_pwd_vat_exemption;
    $restaurant_bill->total_discount = $request->discount['total'];
    $restaurant_bill->gross_billing = $request->gross_billing;
    $restaurant_bill->net_billing = $request->net_billing;
    $restaurant_bill->total_item_amount = $request->total;
    $restaurant_bill->server_id = $customer_data->server_id;
    $restaurant_bill->cashier_id = $request->session()->get('users.user_data')->id;
    $restaurant_bill->restaurant_table_customer_id = $customer_data->id;
    $restaurant_bill->table_name = $customer_data->table_name;
    $restaurant_bill->guest_name = $customer_data->guest_name;
    $restaurant_bill->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_bill->type = "good_order";
    $restaurant_bill->check_number = ($check_number==null?1:++$check_number);
    $restaurant_bill->save();



    $bill_data = $restaurant_bill->orderBy('id','DESC')->first();

    $bill_preview_detail = $request->items;
    foreach ($bill_preview_detail as $preview_data) {
      if(abs($preview_data["quantity_to_bill"]) != 0){
        $restaurant_bill_detail = new Restaurant_bill_detail;
        $restaurant_bill_detail->restaurant_menu_id = $preview_data["restaurant_menu_id"];
        $restaurant_bill_detail->quantity = abs($preview_data["quantity_to_bill"]);
        $restaurant_bill_detail->price = $preview_data["price"];
        $restaurant_bill_detail->special_instruction = $preview_data["special_instruction"];
        $restaurant_bill_detail->restaurant_bill_id = $bill_data->id;
        $restaurant_bill_detail->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
        $restaurant_bill_detail->date_ = strtotime(date("m/d/Y"));
        $restaurant_bill_detail->save();
        $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
        $restaurant_temp_bill_detail_data = $restaurant_temp_bill_detail
          ->where('restaurant_temp_bill_id',$preview_data["restaurant_temp_bill_id"])
          ->where('restaurant_menu_id',$preview_data["restaurant_menu_id"])
          ->first();
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

    return $this->list_bill($request,$id);
  }

  public function show_bill(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_menu = new Restaurant_menu;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
    $data["bill"] = $restaurant_bill->find($id);
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
          $bill_detail_data->menu = $restaurant_menu->find($bill_detail_data->restaurant_menu_id)->name;
          $bill_detail_data->settlement = settlements($bill_detail_data->settlement);
        }
      }else{
        $data["bill_detail"] = $restaurant_bill_detail->where("restaurant_bill_id",$id)->get();
        foreach ($data["bill_detail"] as $bill_detail_data) {
          $bill_detail_data->menu = $restaurant_menu->find($bill_detail_data->restaurant_menu_id)->name;
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
    $restaurant_bill_data = Restaurant_bill::find($id);
    // return $restaurant_bill_data->customer;
    $restaurant_bill_data->deleted_comment = $request->deleted_comment;
    $restaurant_bill_data->deleted_by = $request->session()->get('users.user_data')->id;
    $restaurant_table_customer = new Restaurant_table_customer;
    $customer_data = $restaurant_table_customer->withTrashed()->find($restaurant_bill_data->restaurant_table_customer_id);
    $restaurant_bill_detail_data = Restaurant_bill_detail::where('restaurant_bill_id',$id);
    if($customer_data->deleted_at==null){
      $restaurant_bill_data->is_paid = 0;
      $customer_data->has_billed_completely = 0;
      $customer_data->has_paid = 0;
      $customer_data->save();
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
    }
    $restaurant_bill_data->save();
    $restaurant_bill_data->delete();
    $restaurant_bill_detail_data->delete();
  }

}
