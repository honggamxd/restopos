<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Restaurant_order_cancellation;
use App\Restaurant_order_cancellation_detail;
use App\Restaurant_order;
use App\Restaurant_order_detail;
use App\Restaurant_accepted_order_cancellation;
use App\Restaurant_table_customer;
use App\Restaurant_menu;
use DB;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_payment;

class Restaurant_order_cancellation_controller extends Controller
{
    //

  public function index(Request $request)
  {
    return view('restaurant.cancellations');
  }

  public function show(Request $request,$restaurant_id=false)
  {
    $restaurant_order_cancellation = new Restaurant_order_cancellation;
    if($restaurant_id!=false){
      $data['result'] = $restaurant_order_cancellation->where('restaurant_order_cancellation.restaurant_id',$restaurant_id);
      $data['result']->where('restaurant_order_cancellation.approved_by',0);
      $data['result']->select('restaurant_order_cancellation.*',DB::raw('restaurant.name as restaurant_name'),DB::raw('user.name as cancelled_by_name'));
      $data['result']->join('restaurant','restaurant.id','=','restaurant_order_cancellation.restaurant_id');
      $data['result']->join('user','user.id','=','restaurant_order_cancellation.cancelled_by');
      $data['result'] = $data['result']->get();
    }else{
      $data['result'] = $restaurant_order_cancellation->get();
    }
    return $data;
  }
  public function accept_request(Request $request,$id)
  {
    $restaurant_order_cancellation = new Restaurant_order_cancellation;
    $cancellation_request_data = $restaurant_order_cancellation->find($id);
    foreach ($cancellation_request_data->detail as $cancelled_order_item) {
      $restaurant_order_detail = new Restaurant_order_detail;
      $restaurant_order_detail_data = $restaurant_order_detail->where(['restaurant_menu_id'=>$cancelled_order_item->restaurant_menu_id,'restaurant_order_id'=>$cancellation_request_data->restaurant_order_id])->first();
      if($cancelled_order_item->quantity>0){
        $restaurant_order_detail_data->quantity -= $cancelled_order_item->quantity;
        $restaurant_order_detail_data->save();
        for ($i=0; $i < $cancelled_order_item->quantity; $i++) { 
          $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
          $restaurant_accepted_order_cancellation->restaurant_menu_id = $restaurant_order_detail_data->restaurant_menu_id;
          $restaurant_accepted_order_cancellation->restaurant_table_customer_id = $cancellation_request_data->restaurant_table_customer_id;
          $restaurant_accepted_order_cancellation->quantity = 1;
          $restaurant_accepted_order_cancellation->price = $restaurant_order_detail_data->price;
          $restaurant_accepted_order_cancellation->restaurant_order_cancellation_id = $cancellation_request_data->id;
          $restaurant_accepted_order_cancellation->save();
        }
      }
    }
    $cancellation_request_data->approved_by = $request->session()->get('users.user_data')->id;
    $cancellation_request_data->save();

    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table_customer_data = $restaurant_table_customer->find($cancellation_request_data->restaurant_table_customer_id);
    $restaurant_table_customer_data->cancellation_order_status = 1;
    $restaurant_table_customer_data->save();

    $restaurant_order = new Restaurant_order;
    $order_data = $restaurant_order->find($cancellation_request_data->restaurant_order_id);
    $order_data->has_cancelled = 1;
    $order_data->has_cancellation_request = 0;
    $order_data->save();
    return $cancellation_request_data;
  }
  public function accepted_request(Request $request,$id)
  {
    $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
    $data['cancelled_orders'] = $restaurant_accepted_order_cancellation->where('has_settled',0)->where('quantity','<>','0')->where('restaurant_table_customer_id',$id)->get();
    foreach ($data['cancelled_orders'] as $cancelled_order_item) {
      $restaurant_menu = new Restaurant_menu;
      $cancelled_order_item->menu_name = $restaurant_menu->find($cancelled_order_item->restaurant_menu_id)->name;
      $data['table_customer_id'] = $cancelled_order_item->restaurant_table_customer_id;
    }
    return $data;
  }
  public function delete_request(Request $request,$id)
  {
    # code...
  }

  public function settlement(Request $request,$id)
  {
    // return $request->all();
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table_customer_data = $restaurant_table_customer->find($request->table_customer_id);
    $restaurant_table_customer_data->cancellation_order_status = 2;
    $restaurant_table_customer_data->save();
    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill->date_ = strtotime(date("m/d/Y"));
    $restaurant_bill->date_time = strtotime(date("m/d/Y h:i:s A"));
    $restaurant_bill->server_id = $restaurant_table_customer_data->server_id;
    $restaurant_bill->cashier_id = $request->session()->get('users.user_data')->id;
    $restaurant_bill->restaurant_table_customer_id = $restaurant_table_customer_data->id;
    $restaurant_bill->table_name = $restaurant_table_customer_data->table_name;
    $restaurant_bill->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_bill->is_paid = 1;
    $restaurant_bill->type = "bad_order";
    $restaurant_bill->save();
    // Restaurant_bill_detail
    $restaurant_bill = new Restaurant_bill;
    $bill_data = $restaurant_bill->orderBy('id','DESC')->first();
    $total_item_amount = 0;
    foreach ($request->items as $cancelled_order_item) {
      $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
      $restaurant_accepted_order_cancellation_data = $restaurant_accepted_order_cancellation->find($cancelled_order_item['id']);
      $restaurant_accepted_order_cancellation_data->settlement = $cancelled_order_item['settlement'];
      $restaurant_accepted_order_cancellation_data->has_settled = 1;
      $restaurant_accepted_order_cancellation_data->restaurant_bill_id = $bill_data->id;
      $restaurant_accepted_order_cancellation_data->save();

      $restaurant_bill_detail = new Restaurant_bill_detail;
      $restaurant_bill_detail->restaurant_menu_id = $cancelled_order_item['restaurant_menu_id'];
      $restaurant_bill_detail->restaurant_menu_name = $cancelled_order_item['menu_name'];
      $restaurant_bill_detail->quantity = 1;
      $restaurant_bill_detail->price = $cancelled_order_item['price'];
      $restaurant_bill_detail->date_ = $bill_data->date_;
      $restaurant_bill_detail->restaurant_bill_id = $bill_data->id;
      $restaurant_bill_detail->restaurant_bill_id = $bill_data->id;
      $restaurant_bill_detail->restaurant_id = $bill_data->restaurant_id;
      $restaurant_bill_detail->save();

      $total_item_amount += $cancelled_order_item['price'];
    }
    $bill_data->gross_billing = $total_item_amount;
    $bill_data->net_billing = $total_item_amount;
    $bill_data->total_item_amount = $total_item_amount;
    $bill_data->save();
    $restaurant_accepted_order_cancellation = new Restaurant_accepted_order_cancellation;
    $cancelled_orders_settlements = $restaurant_accepted_order_cancellation->select('settlement',DB::raw('SUM(price) as total'))->groupBy('settlement')->where('restaurant_bill_id',$bill_data->id)->get();
    foreach ($cancelled_orders_settlements as $cancelled_orders_settlements_data) {
      $restaurant_payment = new Restaurant_payment;
      $restaurant_payment->payment = $cancelled_orders_settlements_data->total;
      $restaurant_payment->settlement = $cancelled_orders_settlements_data->settlement;
      $restaurant_payment->restaurant_id = $bill_data->restaurant_id;
      $restaurant_payment->date_ = $bill_data->date_;
      $restaurant_payment->date_time = $bill_data->date_time;
      $restaurant_payment->restaurant_bill_id = $bill_data->id;
      $restaurant_payment->save();
    }
  }
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
      $restaurant_order_cancellation_detail->restaurant_order_cancellation_id = $restaurant_order_cancellation_data->id; 
      $restaurant_order_cancellation_detail->restaurant_menu_id = $order_items['restaurant_menu_id']; 
      $restaurant_order_cancellation_detail->quantity = (isset($order_items['quantity_to_cancel'])?$order_items['quantity_to_cancel']:0); 
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
