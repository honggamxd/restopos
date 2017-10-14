<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Restaurant_order_cancellation;
use App\Restaurant_order_cancellation_detail;
use App\Restaurant_order;

class Restaurant_order_cancellation_controller extends Controller
{
    //
  public function before_bill_out_cancellation_request(Request $request)
  {
    $this->validate($request, [
        'restaurant_order_id' => 'has_cancellation_request:before_bill_out',
        'items' => 'cancellation_request_items:before_bill_out',
      ],[
        'has_cancellation_request' => 'This order has an existing request for cancellation.',
        'cancellation_request_items' => 'Quantity of cancellation must not exceed to the quantity of orders.',
      ]);
    // return $request->all();
    // $user_data = $request->session()->get();
    $restaurant_order_cancellation = new Restaurant_order_cancellation;
    $restaurant_order_cancellation->restaurant_order_id = $request->restaurant_order_id;
    $restaurant_order_cancellation->restaurant_table_customer_id = $request->restaurant_table_customer_id;
    $restaurant_order_cancellation->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_order_cancellation->cancelled_by = $request->session()->get('users.user_data')->id;
    // $restaurant_order_cancellation->reason_cancelled = $request->reason_cancelled;
    $restaurant_order_cancellation->save();
    $restaurant_order_cancellation = new Restaurant_order_cancellation;
    $restaurant_order_cancellation_data = $restaurant_order_cancellation->orderBy('id','DESC')->first();
    foreach ($request->items as $order_items) {
      $restaurant_order_cancellation_detail = new Restaurant_order_cancellation_detail;
      $restaurant_order_cancellation_detail->restaurant_menu_id = $order_items['restaurant_menu_id']; 
      $restaurant_order_cancellation_detail->quantity = $order_items['quantity_to_cancel']; 
      $restaurant_order_cancellation_detail->price = $order_items['price']; 
      $restaurant_order_cancellation_detail->save(); 
    }
    $restaurant_order = new Restaurant_order; 
    $order_data = $restaurant_order->find($request->restaurant_order_id);
    $order_data->has_cancellation_request = 1;
    $order_data->save();
    return $request->all();
  }
}
