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

class Restaurant_table_customer_controller extends Controller
{
  public function store(Request $request)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table_customer->restaurant_table_id = $request->table_id;
    $restaurant_table_customer->restaurant_id = 1;
    $restaurant_table_customer->pax = $request->pax;
    $restaurant_table_customer->server = 1;
    $restaurant_table_customer->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_table_customer->save();
    $restaurant_table = new Restaurant_table;
    $table_occupied = $restaurant_table->find($request->table_id);
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
        $data["cart"]["menu_".$request->menu_id]->special_order = "";
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

  public function update_cart(Request $request,$type,$id)
  {
    if($type=="special_order"){
      $cart_data = $request->session()->get('restaurant.table_customer.'.$id.".cart");
      $cart_data["menu_".$request->menu_id]->special_order = $request->special_order;
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
    $data["result"] = $restaurant_table_customer->get();
    foreach ($data["result"] as $customer_data) {
      $customer_data->date_time = date("h:i:s A",$customer_data->date_time);
      $customer_data->has_order = ($customer_data->has_order==1?TRUE:FALSE);
      $customer_data->has_billed_out = ($customer_data->has_billed_out==1?TRUE:FALSE);
      $customer_data->has_bill = ($customer_data->has_bill==1?TRUE:FALSE);
      $customer_data->table_name = $restaurant_table->where("id",$customer_data->restaurant_table_id)->value("name");
      $customer_data->total = $restaurant_order
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->select( DB::raw('SUM(quantity*price) as total'))
        ->where("restaurant_table_customer_id",$customer_data->id)
        ->first()
        ->total;
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
        ->select('special_order','restaurant_menu_id','price',DB::raw('SUM(quantity) as total_quantity'))
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->where('restaurant_table_customer_id',$id)
        ->where('restaurant_menu_id',$order_item_data->restaurant_menu_id)
        ->first();
      $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
      $restaurant_temp_bill_detail->restaurant_menu_id = $order_joined_data->restaurant_menu_id;
      $restaurant_temp_bill_detail->price = $order_joined_data->price;
      $restaurant_temp_bill_detail->quantity = $order_joined_data->total_quantity;
      $restaurant_temp_bill_detail->special_order = $order_joined_data->special_order;
      $restaurant_temp_bill_detail->restaurant_temp_bill_id = $temp_bill_data->id;
      $restaurant_temp_bill_detail->save();
    }
    $data["result"] = $restaurant_temp_bill
      ->join('restaurant_temp_bill_detail','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
      ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();
    return $data;
  }

  public function show_temp_bill(Request $request,$id)
  {
    $restaurant_temp_bill = new Restaurant_temp_bill;
    $data["result"] = $restaurant_temp_bill
      ->join('restaurant_temp_bill_detail', 'restaurant_temp_bill.id', '=', 'restaurant_temp_bill_detail.restaurant_temp_bill_id')
      ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();
    return $data;
  }

  public function show_order(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $data["result"] = $restaurant_order->where('restaurant_table_customer_id',$id)->get();
    return $data;
  }

  public function make_bill(Request $request,$id)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table = new Restaurant_table;
    $restaurant_temp_bill = new Restaurant_temp_bill;
    $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
    $customer_data = $restaurant_table_customer->find($id);
    
    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill->date_ = strtotime(date("m/d/Y"));
    $restaurant_bill->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_bill->pax = $customer_data->pax;
    $restaurant_bill->server = $customer_data->server;
    $restaurant_bill->cashier = 0;
    $restaurant_bill->restaurant_table_customer_id = $customer_data->id;
    $restaurant_bill->table_name = $restaurant_table->where("id",$customer_data->restaurant_table_id)->value("name");
    $restaurant_bill->restaurant_id = $customer_data->restaurant_id;
    $restaurant_bill->save();

    $customer_data->has_bill = 1;
    $customer_data->save();

    $bill_data = $restaurant_bill->orderBy('id','DESC')->first();
    $restaurant_bill_detail = new Restaurant_bill_detail;

    $bill_preview_detail = $this->show_temp_bill($request,$id);
    foreach ($bill_preview_detail["result"] as $preview_data) {
      $restaurant_bill_detail = new Restaurant_bill_detail;
      $restaurant_bill_detail->restaurant_menu_id = $preview_data->restaurant_menu_id;
      $restaurant_bill_detail->quantity = $preview_data->quantity;
      $restaurant_bill_detail->price = $preview_data->price;
      $restaurant_bill_detail->special_order = $preview_data->special_order;
      $restaurant_bill_detail->restaurant_bill_id = $bill_data->id;
      $restaurant_bill_detail->restaurant_id = $preview_data->restaurant_id;
      $restaurant_bill_detail->save();
    }

    return $this->list_bill($request,$id);
  }

  public function show_bill(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_menu = new Restaurant_menu;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $data["bill"] = $restaurant_bill->find($id);
    $data["bill"]->date_ = date("F d, Y",$data["bill"]->date_);
    $data["bill"]->date_time = date("h:i:s A",$data["bill"]->date_time);
    $data["bill_detail"] = $restaurant_bill_detail->where("restaurant_bill_id",$id)->get();
    $data["total"] = 0;
    foreach ($data["bill_detail"] as $bill_detail_data) {
      $bill_detail_data->menu = $restaurant_menu->find($bill_detail_data->restaurant_menu_id)->name;
      $data["total"] += $bill_detail_data->price*$bill_detail_data->quantity;
    }
    $data["vat"] = $data["total"]*0.12;
    $data["sc"] = $data["total"]*0.1;
    $data["sub_total"] = $data["total"]-$data["vat"]-$data["sc"];
    return $data;
  }
  public function list_bill(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $data["result"] = $restaurant_bill->where("restaurant_table_customer_id",$id)->get(); 
    foreach ($data["result"] as $bill_data) {
      $data["table_name"] = $bill_data->table_name;
      $bill_data->total = $restaurant_bill_detail->select(DB::raw('SUM(price*quantity) as total'))->where("restaurant_bill_id",$bill_data->id)->first()->total;
    }
    return $data;
  }

  public function destroy(Request $request,$id)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table = new Restaurant_table;
    $customer_data = $restaurant_table_customer->find($id);
    $table_data = $restaurant_table->where("id",$customer_data->restaurant_table_id)->first();
    $table_data->occupied = 0;
    $table_data->save();
    $restaurant_table_customer->where("id",$id)->delete();
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
}
