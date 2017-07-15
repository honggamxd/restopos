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
      $customer_data->table_name = $restaurant_table->where("id",$customer_data->restaurant_table_id)->value("name");
      $customer_data->total = $restaurant_order
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->select( DB::raw('SUM(quantity*price) as total'))
        ->where("restaurant_table_customer_id",$customer_data->id)->first()->total;
    }
    return $data;
  }

  public function bill_out(Request $request,$id)
  {
    # code...
  }

  public function show_order(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $data["result"] = $restaurant_order->where('restaurant_table_customer_id',$id)->get();
    return $data;
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
