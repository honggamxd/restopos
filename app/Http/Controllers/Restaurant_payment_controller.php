<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_payment;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_table_customer;

class Restaurant_payment_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function store(Request $request,$id)
  {
    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $restaurant_table_customer = new Restaurant_table_customer;
    $bill_data = $restaurant_bill->find($id);
    foreach ($request->settlement as $settlement) {
      $restaurant_payment = new Restaurant_payment;
      $restaurant_payment->settlement = $settlement;
      $restaurant_payment->payment = $request->settlements_amount[$settlement];
      $restaurant_payment->restaurant_id = $bill_data->restaurant_id;
      $restaurant_payment->date_ = strtotime(date("m/d/Y"));
      $restaurant_payment->date_time = strtotime(date("m/d/Y h:i:s A"));
      $restaurant_payment->restaurant_bill_id = $id;
      $restaurant_payment->save();
    }
    $bill_data->is_paid = 1;
    $bill_data->excess = $request->excess;
    $bill_data->save();
    $data["result"] = $restaurant_bill->where("restaurant_table_customer_id",$bill_data->restaurant_table_customer_id)->get();
    $has_unpaid_order = false;
    $customer_data = $restaurant_table_customer->where("id",$bill_data->restaurant_table_customer_id)->first();
    foreach ($data["result"] as $bill_data) {
      $data["table_name"] = $bill_data->table_name;
      $bill_data->total = $restaurant_bill_detail->select(DB::raw('SUM(price*quantity) as total'))->where("restaurant_bill_id",$bill_data->id)->first()->total;
      $bill_data->check_number = sprintf('%04d',$bill_data->check_number);
      if($bill_data->is_paid==0){
        $has_unpaid_order = true;
      }
    }
    if($customer_data->has_billed_completely==1){
      $customer_data->has_paid = ($has_unpaid_order?0:1);
    }
    $customer_data->save();
    return $data;
  }
  public function show(Request $request,$id)
  {
    $restaurant_payment = new Restaurant_payment;
    $restaurant_bill = new Restaurant_bill;
    $data["result"] = $restaurant_payment->where("restaurant_bill_id",$id)->get();
    if($restaurant_bill->find($id)!=null){
      foreach ($data["result"] as $payment_data) {
        $payment_data->settlement = settlements($payment_data->settlement);
      }
      $data["excess"] = $restaurant_bill->find($id)->excess;
      return $data;
    }
  }
}
