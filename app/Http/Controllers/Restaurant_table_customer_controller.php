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
use App\restaurant_order_cancellation;

class Restaurant_table_customer_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function store(Request $request)
  {
    $this->validate($request, [
        'table_id.id' => 'required',
        'server_id.id' => 'required',
        'pax' => 'integer|required|custom_min:0',
    ],[
      'custom_min' => 'The number of :attribute must be greater than 0.',
      'server_id.id.required' => 'The Server field is required.'
    ]);
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table_customer->restaurant_table_id = $request->table_id["id"];
    $restaurant_table_customer->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;;
    $restaurant_table_customer->table_name = $request->table_id["name"];
    $restaurant_table_customer->guest_name = ($request->guest_name==null?"":$request->guest_name);
    $restaurant_table_customer->pax = $request->pax;
    $restaurant_table_customer->sc_pwd = $request->sc_pwd;
    $restaurant_table_customer->server_id = $request->server_id["id"];
    $restaurant_table_customer->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_table_customer->save();
    $restaurant_table = new Restaurant_table;
    $table_occupied = $restaurant_table->find($request->table_id["id"]);
    $table_occupied->occupied = 1;
    $table_occupied->save();
  }

  public function order_cart(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    
    //guide
    //restaurant.table_customer.table_customer_id.menu_id
    $data["cart"]["menu_".$request->menu_id] = $restaurant_menu->where("id",$request->menu_id)->where(["is_prepared"=>1,"deleted"=>0])->first();
    if ($request->session()->has('restaurant.table_customer.'.$request->table_customer_id.'.cart.'.'menu_'.$request->menu_id)
      && $request->session()->get('restaurant.table_customer.'.$request->table_customer_id.'.cart.'.'menu_'.$request->menu_id) != array()) {
      $data = $request->session()->get('restaurant.table_customer.'.$request->table_customer_id);
      $data["cart"]["menu_".$request->menu_id]->quantity = $data["cart"]["menu_".$request->menu_id]->quantity+1;
      $data["cart"]["menu_".$request->menu_id]->total = $data["cart"]["menu_".$request->menu_id]->quantity*$data["cart"]["menu_".$request->menu_id]->price;
      $request->session()->put('restaurant.table_customer.'.$request->table_customer_id, $data);
    }else{
      if ($request->session()->has('restaurant.table_customer.'.$request->table_customer_id.'.cart')
      && $request->session()->get('restaurant.table_customer.'.$request->table_customer_id.'.cart') != array()) {
        $data = $request->session()->get('restaurant.table_customer.'.$request->table_customer_id);
      }
      $data["cart"]["menu_".$request->menu_id] = $restaurant_menu->where("id",$request->menu_id)->where(["is_prepared"=>1,"deleted"=>0])->first();
      if($data["cart"]["menu_".$request->menu_id] != NULL){
        // $data["menu_".$request->menu_id]->price = 0;
        $data["cart"]["menu_".$request->menu_id]->quantity = 1;
        $data["cart"]["menu_".$request->menu_id]->special_instruction = "";
        $data["cart"]["menu_".$request->menu_id]->total = $data["cart"]["menu_".$request->menu_id]->quantity*$data["cart"]["menu_".$request->menu_id]->price;
        $request->session()->put('restaurant.table_customer.'.$request->table_customer_id, $data);
      }else{
        $data = array();
        $data["error"] = "This menu is not available";
        return response($data, 422);
      }
    }
    $data["cart"] = $request->session()->get('restaurant.table_customer.'.$request->table_customer_id.".cart");
    $total = 0;
    foreach ($data["cart"] as $cart_data) {
      $total += $cart_data->quantity*$cart_data->price;
    }
    $data["total"] = $total;
    return $data;
  }

  public function update_cart_item(Request $request,$type,$id)
  {
    if($type=="special_instruction"){
      $cart_data = $request->session()->get('restaurant.table_customer.'.$id.".cart");
      $cart_data["menu_".$request->menu_id]->special_instruction = $request->special_instruction;
      $request->session()->put('restaurant.table_customer.'.$id.".cart",$cart_data);
      return $this->show($request,$id);
    }elseif ($type=="quantity") {
      if($request->quantity != 0){
        $cart_data = $request->session()->get('restaurant.table_customer.'.$id.".cart");
        $cart_data["menu_".$request->menu_id]->quantity = abs($request->quantity);
        $cart_data["menu_".$request->menu_id]->total = $cart_data["menu_".$request->menu_id]->quantity * $cart_data["menu_".$request->menu_id]->price;
        $request->session()->put('restaurant.table_customer.'.$id.".cart",$cart_data);
      }
      return $this->show($request,$id);
    }
  }

  public function remove_cart_item(Request $request)
  {
    if($request->session()->has('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id)&&$request->session()->get('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id)!=array()){
      $request->session()->forget('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id);
    }
    return $this->show($request,$request->table_customer_id);
    // if($request->session()->has())
  }

  public function show(Request $request,$table_customer_id)
  {
    $data = array();
    if($request->session()->has('restaurant.table_customer.'.$table_customer_id.".cart") && $request->session()->get('restaurant.table_customer.'.$table_customer_id.".cart") != array()){
      $data["cart"] = $request->session()->get('restaurant.table_customer.'.$table_customer_id.".cart");
      $total = 0;
      foreach ($data["cart"] as $cart_data) {
        $total += $cart_data->quantity*$cart_data->price;
      }
      $data["total"] = $total;
    }else{
      $data["cart"] = array();
      $data["total"] = "";
    }
    return $data;
  }

  public function get_list(Request $request)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_order_detail = new Restaurant_order_detail;
    $restaurant_table = new Restaurant_table;
    $restaurant_order = new Restaurant_order;
    $data["result"] = $restaurant_table_customer->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)->get();
    foreach ($data["result"] as $customer_data) {
      $customer_data->date_time = date("h:i:s A",$customer_data->date_time);
      $customer_data->has_order = ($customer_data->has_order==1?TRUE:FALSE);
      $customer_data->has_billed_out = ($customer_data->has_billed_out==1?TRUE:FALSE);
      $customer_data->has_bill = ($customer_data->has_bill==1?TRUE:FALSE);
      $customer_data->server_name = DB::table('restaurant_server')->find($customer_data->server_id)->name;
      $customer_data->total = $restaurant_order
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->select( DB::raw('SUM(quantity*price) as total'))
        ->where("restaurant_table_customer_id",$customer_data->id)
        ->first()
        ->total;
      $customer_data->table_data = $restaurant_table->find($customer_data->restaurant_table_id);
    }
    return $data;
  }

  public function bill_out(Request $request,$id)
  {
    $restaurant_temp_bill = new Restaurant_temp_bill;
    $restaurant_order = new Restaurant_order;
    $unique_ordered_items = $restaurant_order
      ->select('restaurant_menu_id')
      ->distinct()
      ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();
    $restaurant_temp_bill->restaurant_table_customer_id = $id;
    $restaurant_temp_bill->save();
    $temp_bill_data = $restaurant_temp_bill->orderBy('id','DESC')->first();

    $restaurant_table_customer = new Restaurant_table_customer;
    $customer_data = $restaurant_table_customer->find($id);
    $customer_data->has_billed_out = 1;
    $customer_data->restaurant_temp_bill_id = $temp_bill_data->id;
    $customer_data->save();

    foreach ($unique_ordered_items as $order_item_data) {
      $order_joined_data = $restaurant_order
        ->select('special_instruction','restaurant_menu_id','price',DB::raw('SUM(quantity) as total_quantity'))
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->where('restaurant_table_customer_id',$id)
        ->where('restaurant_menu_id',$order_item_data->restaurant_menu_id)
        ->first();
      $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
      $restaurant_temp_bill_detail->restaurant_menu_id = $order_joined_data->restaurant_menu_id;
      $restaurant_temp_bill_detail->price = $order_joined_data->price;
      $restaurant_temp_bill_detail->quantity = $order_joined_data->total_quantity;
      $restaurant_temp_bill_detail->special_instruction = $order_joined_data->special_instruction;
      $restaurant_temp_bill_detail->restaurant_temp_bill_id = $temp_bill_data->id;
      $restaurant_temp_bill_detail->save();
    }
    $data["result"] = $restaurant_temp_bill
      ->join('restaurant_temp_bill_detail','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
      ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();

    $temp_bill_remaining_quantity = $restaurant_temp_bill_detail
    ->join('restaurant_temp_bill','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
    ->select('restaurant_temp_bill.*',DB::raw('SUM(restaurant_temp_bill_detail.quantity) as total'))
    ->where('restaurant_temp_bill.restaurant_table_customer_id',$id)
    ->value('total');

    $restaurant_table_customer = new Restaurant_table_customer;
    $customer_data = $restaurant_table_customer->find($id);
    if($temp_bill_remaining_quantity==0){
      $customer_data->cancellation_order_status = 1;
      $customer_data->has_billed_completely = 1;
      $customer_data->has_paid = 1;
    }
    $customer_data->save();


    $restaurant_order_cancellation = new Restaurant_order_cancellation;
    $restaurant_order_cancellation_data = $restaurant_order_cancellation->where('approved',0)->where('restaurant_table_customer_id',$id);
    if($restaurant_order_cancellation_data->get()!=array()){
      $customer_data->has_cancellation_request = 0;
      $customer_data->save();
      $restaurant_order_cancellation_data->delete();
    }
    return $this->show_temp_bill($request,$id);
  }

  public function show_temp_bill(Request $request,$id)
  {
    $restaurant_temp_bill = new Restaurant_temp_bill;
    $data["result"] = $restaurant_temp_bill
      ->join('restaurant_temp_bill_detail', 'restaurant_temp_bill.id', '=', 'restaurant_temp_bill_detail.restaurant_temp_bill_id')
      ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();
    $restaurant_temp_bill_data = $restaurant_temp_bill->where('restaurant_table_customer_id',$id)->first();
    $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
    $restaurant_table_customer = new Restaurant_table_customer;
    $data['customer_data'] = $restaurant_table_customer->find($id);
    $data["total"] = $restaurant_temp_bill_detail
      ->select(DB::raw('SUM(price*quantity) as total'))
      ->where('restaurant_temp_bill_id',$restaurant_temp_bill_data->id)
      ->value("total");
    $data['discount']['amount_disount'] = 0;
    $data['discount']['percent_disount'] = 0;
    $data['discount']['total'] = 0;
    $data['discount']['sc_pwd_discount'] = $data["total"]*$data['customer_data']->sc_pwd/$data['customer_data']->pax/1.12*0.2;
    $data['discount']['sc_pwd_vat_exemption'] = $data["total"]*$data['customer_data']->sc_pwd/$data['customer_data']->pax/1.12*0.12;
    $data['gross_billing'] = $data["total"];
    $data['net_billing'] = round($data["total"]-$data['discount']['sc_pwd_discount']-$data['discount']['sc_pwd_vat_exemption'],2);
    return $data;
  }

  public function show_order(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $data["result"] = $restaurant_order->where('restaurant_table_customer_id',$id)->get();
    $restaurant_table_customer = new Restaurant_table_customer;
    $data['customer_data'] = $restaurant_table_customer->find($id);
    return $data;
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
    $restaurant_bill->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_bill->type = "good_order";
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
    $data["bill"]->date_ = date("F d, Y",$data["bill"]->date_);
    $data["bill"]->date_time = date("h:i:s A",$data["bill"]->date_time);
    $data["bill"]->restaurant_name = DB::table('restaurant')->find($data["bill"]->restaurant_id)->name;
    $data["bill"]->cashier_name = DB::table('user')->find($data["bill"]->cashier_id)->name;
    $data["bill"]->server_name = DB::table('restaurant_server')->find($data["bill"]->server_id)->name;
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
    }
    $data["has_paid"] = ($has_unpaid_order?0:1);
    return $data;
  }

  public function destroy(Request $request,$id)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table = new Restaurant_table;
    $customer_data = $restaurant_table_customer->find($id);

    $customers = $restaurant_table_customer->where('restaurant_table_id',$customer_data->restaurant_table_id);
    // return $customers->count();
    if($customers->count()==1){
      $table_data = $restaurant_table->where("id",$customer_data->restaurant_table_id)->first();
      $table_data->occupied = 0;
      $table_data->save();
    }
    $restaurant_table_customer->find($id)->delete();
  }

  public function test(Request $request)
  {
    return ($request->session()->get('restaurant'));
    // return $request->session()->get('restaurant.table_customer.9');
  }
  
  public function delete(Request $request)
  {
    $request->session()->forget('restaurant');
    # code...
  }

  public function update(Request $request,$id)
  {
    // return $request->customer_data['table_data'];
    // exit;
    $restaurant_table_customer = new Restaurant_table_customer;
    $data["new_table"] = $request->table_id;
    $data["old_table"] = $request->customer_data['table_data'];
    $customer_data = $restaurant_table_customer->find($id);
    $customer_data->pax = $request->pax;
    $customer_data->sc_pwd = $request->sc_pwd;
    $customer_data->guest_name = $request->guest_name;
    if($data["new_table"]['id']!=$data["old_table"]['id']){
      $customer_data->table_name .= '>'.$data["new_table"]['name'];
      $customer_data->restaurant_table_id = $data["new_table"]['id'];
    }
    $customer_data->save();
    $customer_data = $restaurant_table_customer->find($id);
    $customers = $restaurant_table_customer->where('restaurant_table_id',$data["old_table"]['id']);
    if($customers->count()==0){
      $restaurant_table = new Restaurant_table;
      $table_data = $restaurant_table->find($data["old_table"]['id']);
      $table_data->occupied = 0;
      $table_data->save();
    }else{
      $restaurant_table = new Restaurant_table;
      $table_data = $restaurant_table->find($data["old_table"]['id']);
      $table_data->occupied = 1;
      $table_data->save();
    }

    $customers = $restaurant_table_customer->where('restaurant_table_id',$data["new_table"]['id']);
    if($customers->count()==0){
      $restaurant_table = new Restaurant_table;
      $table_data = $restaurant_table->find($data["new_table"]['id']);
      $table_data->occupied = 0;
      $table_data->save();
    }else{
      $restaurant_table = new Restaurant_table;
      $table_data = $restaurant_table->find($data["new_table"]['id']);
      $table_data->occupied = 1;
      $table_data->save();
    }


    return $data;
  }
}
