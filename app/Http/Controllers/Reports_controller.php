<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_menu;
use App\Restaurant_payment;
use DB;

class Reports_controller extends Controller
{
  public function index(Request $request)
  {
    return view('reports');
  }

  public function restaurant(Request $request,$type)
  {
    if($type=="all"){
      $app_config = DB::table('app_config')->first();
      $data["categories"] = explode(',', $app_config->categories);
      $data["settlements"] = explode(',', $app_config->settlements);
      return view('restaurant.reports.all',$data);
    }
  }
  public function restaurant_reports(Request $request,$type)
  {
    if($type=="all"){
      $restaurant_bill = new Restaurant_bill;
      $restaurant_bill_detail = new Restaurant_bill_detail;
      $restaurant_payment = new Restaurant_payment;
      $bills = $restaurant_bill->get();
      $app_config = DB::table('app_config')->first();
      $categories = $app_config->categories;
      $categories = explode(',', $categories);

      $settlements = $app_config->settlements;
      $settlements = explode(',', $settlements);
      foreach ($bills as $bill_data) {
        $bill_data->date_time = date("h:i:s A",$bill_data->date_time);
        $bill_data->date_ = date("j-M",$bill_data->date_);
        $bill_data->total = 0;
        foreach ($categories as $category) {
           $bill_data->$category = $restaurant_bill_detail
             ->join('restaurant_bill','restaurant_bill_detail.restaurant_bill_id','=','restaurant_bill.id')
             ->join('restaurant_menu','restaurant_bill_detail.restaurant_menu_id','=','restaurant_menu.id')
             ->where('restaurant_bill_id',$bill_data->id)
             ->where('category',$category)
             ->select('restaurant_bill_detail.*','restaurant_menu.category',DB::raw('SUM(restaurant_bill_detail.price*quantity) as total'))
             ->first()->total;
          $bill_data->total += $bill_data->$category;
        }

        foreach ($settlements as $settlement) {
          $bill_data->$settlement = $restaurant_payment->where(['restaurant_bill_id'=>$bill_data->id,'settlement'=>$settlement])->value('payment');
        }

      }
      $data["result"] = $bills;
    }elseif ($type=="settlements") {
      # code...
    }
    return $data;
  }
}
