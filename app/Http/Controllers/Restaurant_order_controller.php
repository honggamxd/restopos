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
use App\Restaurant_order_cancellation;
use App\Restaurant_order_cancellation_detail;


class Restaurant_order_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function store(Request $request,$id)
  {
    $this->validate($request, [
        'table_customer_cart' => 'required'
    ],[
      'table_customer_cart.required' => 'Cannot place an empty order.'
    ]);
    DB::beginTransaction();
    try{
        
        $restaurant_table_customer = new Restaurant_table_customer;
        $customer_data = $restaurant_table_customer->find($id);

        $restaurant_table = new Restaurant_table;
        $table_data = $restaurant_table->find($customer_data->restaurant_table_id);



        $restaurant_order = new Restaurant_order;
        $que_number = $restaurant_order
          ->where('date_',strtotime(date('m/d/Y')))
          ->where('restaurant_id',$customer_data->restaurant_id)
          ->orderBy('id','DESC')
          ->value('que_number');

        $restaurant_order->date_ = strtotime(date("m/d/Y"));
        $restaurant_order->date_time = strtotime(date("m/d/Y h:i:s A"));
        $restaurant_order->pax = $customer_data->pax;
        $restaurant_order->table_name = $customer_data->table_name;
        $restaurant_order->que_number = ($que_number==null?1:++$que_number);
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
          $restaurant_order_detail->restaurant_menu_name = Restaurant_menu::find($cart_data->id)->name;
          $restaurant_order_detail->quantity = $cart_data->quantity;
          $restaurant_order_detail->price = $cart_data->price;
          $restaurant_order_detail->special_instruction = $cart_data->special_instruction;
          $restaurant_order_detail->restaurant_order_id = $order_data->id;
          $restaurant_order_detail->restaurant_id = $customer_data->restaurant_id;
          $restaurant_order_detail->save();
        }
        $cart = $request->session()->forget('restaurant.table_customer.'.$id.'.cart');
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
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
    $data["order"]->que_number = sprintf('%04d',$data['order']->que_number);
    $data["order"]->id_format = sprintf('%04d',$data['order']->id);
    $data["order"]->cancellation_message = Restaurant_order_cancellation::where('restaurant_order_id',$data['order']->id)->orderBy('id','DESC')->value('reason_cancelled');

    $restaurant_table_customer = new Restaurant_table_customer;
    $data['customer_data'] = $restaurant_table_customer->find($data['order']->restaurant_table_customer_id);
    return $data;
  }

  public function index(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $restaurant_order_detail = new Restaurant_order_detail;
    $restaurant_menu = new Restaurant_menu;
    $data["order"] = $restaurant_order->find($id);
    $data["order_detail"] = $restaurant_order_detail->where("restaurant_order_id",$id)->get();
    DB::enableQueryLog();
    foreach ($data["order_detail"] as $order_detail) {
      
      $order_detail->menu = $restaurant_menu->find($order_detail->restaurant_menu_id)->name;
      if($data["order"]->has_cancelled==1){
        $order_detail->cancelled_quantity = DB::table('restaurant_accepted_order_cancellation')
        ->select(DB::raw('SUM(quantity) as total'))
        ->where('restaurant_menu_id',$order_detail->restaurant_menu_id)
        ->where('restaurant_table_customer_id',$data["order"]->restaurant_table_customer_id)
        ->value('total');
      }
      if($order_detail->cancelled_quantity==null){
        $order_detail->cancelled_quantity = 0;
      }
    }
    $data["getQueryLog"] = DB::getQueryLog();
    $data["order"]->date_ = date("F d, Y",$data["order"]->date_);
    $data["order"]->date_time = date("h:i:s A",$data["order"]->date_time);
    $data["order"]->restaurant_name = DB::table('restaurant')->find($data["order"]->restaurant_id)->name;
    $data["order"]->server_name = DB::table('restaurant_server')->find($data["order"]->server_id)->name;
    $data["order"]->que_number = sprintf('%04d',$data['order']->que_number);
    $data["order"]->id_format = sprintf('%04d',$data['order']->id);
    $data["order"]->cancellation_message = Restaurant_order_cancellation::where('restaurant_order_id',$data['order']->id)->orderBy('id','DESC')->value('reason_cancelled');
    $data["id"] = $id;
    $data["print"] = $request->print;
    // return $data;
    return view("restaurant.order",$data);
  }
}
