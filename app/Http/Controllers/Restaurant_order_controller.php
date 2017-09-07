<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use App\Http\Requests\StoreOrdersRequest;
use App\Restaurant_table_customer;
use App\Restaurant_table;
use App\Restaurant_menu;
use App\Restaurant_order;
use App\Restaurant_order_detail;
use App\Restaurant_menu_ingredients;


class Restaurant_order_controller extends Controller
{
  public function store(Request $request,$id)
  {
    $validator = Validator::make($request->all(), [
        'table_customer_cart' => 'required',
        // 'body' => 'required',
    ]);

    $restaurant_table_customer = new Restaurant_table_customer;
    $customer_data = $restaurant_table_customer->find($id);

    $restaurant_table = new Restaurant_table;
    $table_data = $restaurant_table->find($customer_data->restaurant_table_id);

    $restaurant_order = new Restaurant_order;
    $restaurant_order->date_ = strtotime(date("m/d/Y"));
    $restaurant_order->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_order->pax = $customer_data->pax;
    $restaurant_order->table_name = $customer_data->table_name;
    $restaurant_order->restaurant_id = $customer_data->restaurant_id;
    $restaurant_order->restaurant_table_customer_id = $id;
    $restaurant_order->server_id = $customer_data->server_id;
    $restaurant_order->save();

    $customer_data->has_order = 1;
    $customer_data->save();

    $order_data = $restaurant_order->orderBy('id','DESC')->first();
    $cart = $request->session()->get('restaurant.table_customer.'.$id.'.cart');
    foreach ($cart as $cart_data) {
      $restaurant_order_detail = new Restaurant_order_detail;
      $restaurant_order_detail->restaurant_menu_id = $cart_data->id;
      $restaurant_order_detail->quantity = $cart_data->quantity;
      $restaurant_order_detail->price = $cart_data->price;
      $restaurant_order_detail->special_instruction = $cart_data->special_instruction;
      $restaurant_order_detail->restaurant_order_id = $order_data->id;
      $restaurant_order_detail->restaurant_id = $customer_data->restaurant_id;
      $restaurant_order_detail->save();

      $restaurant_menu_ingredients = new Restaurant_menu_ingredients;
      // $menu_ingredients = $restaurant_menu_ingredients->where('restaurant_inventory_id',$cart_data->id);
    }
    $cart = $request->session()->forget('restaurant.table_customer.'.$id.'.cart');
    return $order_data;
  }

  public function show(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $restaurant_order_detail = new Restaurant_order_detail;
    $restaurant_menu = new Restaurant_menu;
    $data["order"] = $restaurant_order->find($id);
    $data["order_detail"] = $restaurant_order_detail->where("restaurant_order_id",$id)->get();
    foreach ($data["order_detail"] as $order_detail) {
      $order_detail->menu = $restaurant_menu->find($order_detail->restaurant_menu_id)->name;
    }
    $data["order"]->date_ = date("F d, Y",$data["order"]->date_);
    $data["order"]->date_time = date("h:i:s A",$data["order"]->date_time);
    $data["order"]->restaurant_name = DB::table('restaurant')->find($data["order"]->restaurant_id)->name;
    $data["order"]->server_name = DB::table('restaurant_server')->find($data["order"]->server_id)->name;
    return $data;
  }

  public function index(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $restaurant_order_detail = new Restaurant_order_detail;
    $restaurant_menu = new Restaurant_menu;
    $data["order"] = $restaurant_order->find($id);
    $data["order_detail"] = $restaurant_order_detail->where("restaurant_order_id",$id)->get();
    foreach ($data["order_detail"] as $order_detail) {
      $order_detail->menu = $restaurant_menu->find($order_detail->restaurant_menu_id)->name;
    }
    $data["order"]->date_ = date("F d, Y",$data["order"]->date_);
    $data["order"]->date_time = date("h:i:s A",$data["order"]->date_time);
    $data["order"]->restaurant_name = DB::table('restaurant')->find($data["order"]->restaurant_id)->name;
    $data["order"]->server_name = DB::table('restaurant_server')->find($data["order"]->server_id)->name;
    $data["id"] = $id;
    $data["print"] = $request->print;
    return view("restaurant.order",$data);
  }
}
