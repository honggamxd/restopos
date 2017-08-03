<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_menu;
use App\Restaurant_payment;
use App\Purchase;
use App\Purchase_detail;
use App\Issuance;
use App\Issuance_detail;
use DB;

class Reports_controller extends Controller
{
  public function index(Request $request)
  {
    return view('reports.index');
  }

  public function show(Request $request,$type)
  {
    $data["date_from"] = $request->get('date_from');
    $data["date_to"] = $request->get('date_to');
    if($type=="all"){
      $app_config = DB::table('app_config')->first();
      $data["categories"] = explode(',', $app_config->categories);
      $data["settlements"] = explode(',', $app_config->settlements);
      return view('reports.all',$data);
    }elseif ($type=="purchases") {
      # code...
      return view('reports.purchases',$data);
    }else{
      return view('reports.issuances',$data);
    }
  } 

  public function show_print(Request $request,$type)
  {
    if($type=="all"){
      $app_config = DB::table('app_config')->first();
      $data["categories"] = explode(',', $app_config->categories);
      $data["settlements"] = explode(',', $app_config->settlements);
      $data["date_from"] = $request->get('date_from');
      $data["date_to"] = $request->get('date_to');
      return view('reports.printable.all',$data);
    }
  }
  public function api(Request $request,$type)
  {
    if($type=="all"){
      DB::enableQueryLog();
      
      $app_config = DB::table('app_config')->first();
      $categories = $app_config->categories;
      $categories = explode(',', $categories);

      $settlements = $app_config->settlements;
      $settlements = explode(',', $settlements);

      $page = $request->page;
      $display_per_page = $request->display_per_page;
      $limit = ($page*$display_per_page)-$display_per_page;

      $restaurant_bill = new Restaurant_bill;
      $restaurant_bill_detail = new Restaurant_bill_detail;
      $restaurant_payment = new Restaurant_payment;
      $bills = $restaurant_bill->where('deleted',0);
      $bills->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
      $num_items = $bills->count();
      if($request->paging=="true"){
        $bills->skip($limit);
        $bills->take($display_per_page);
      }else{
        $display_per_page = $bills->count();
      }
      $bills = $bills->get();
      $data["footer"]["pax"] = $restaurant_bill
        ->select('*',DB::raw('SUM(pax) as total_pax'))
        ->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)])
        ->where('deleted',0)->value('total_pax');
      $data["footer"]["excess"] = $restaurant_bill
        ->select('*',DB::raw('SUM(excess) as total_excess'))
        ->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)])
        ->where('deleted',0)
        ->value('total_excess');

      $data["footer"]["total"] = 0;
      foreach ($categories as $category) {
        $category_total = $restaurant_bill_detail->join('restaurant_bill','restaurant_bill.id','=','restaurant_bill_detail.restaurant_bill_id');
        $category_total->join('restaurant_menu','restaurant_bill_detail.restaurant_menu_id','=','restaurant_menu.id');
        $category_total->where('restaurant_bill.deleted',0);
        $category_total->whereBetween('restaurant_bill.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
        $category_total->select(
            'restaurant_bill.*',
            'restaurant_bill_detail.*',
            'restaurant_menu.category',
            DB::raw('SUM(restaurant_bill_detail.price*restaurant_bill_detail.quantity) as total')
            );
        $category_total->where('restaurant_menu.category',$category);
        $category_total->where('restaurant_bill.deleted',0);
        $data["footer"][$category] = $category_total->value('total');
        $data["footer"]["total"] += $category_total->value('total');
      }

      foreach ($settlements as $settlement) {
        $settlement_total = $restaurant_bill->join('restaurant_payment','restaurant_bill.id','=','restaurant_payment.restaurant_bill_id');
        $settlement_total->where('restaurant_bill.deleted',0);
        $settlement_total->whereBetween('restaurant_payment.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
        $settlement_total->select(
            'restaurant_bill.*',
            'restaurant_payment.payment',
            'restaurant_payment.settlement',
            DB::raw('SUM(restaurant_payment.payment) as total')
            );
        $settlement_total->where('restaurant_payment.settlement',$settlement);
        $settlement_total->where('restaurant_bill.deleted',0);
        $data["footer"][$settlement] = $settlement_total->value('total');
      }

      $data["paging"] = paging($page,$num_items,$display_per_page);

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
      $data["getQueryLog"] = DB::getQueryLog();
    }elseif ($type=="purchases") {
      DB::enableQueryLog();
      $purchase = new Purchase;
      $purchase_detail = new Purchase_detail;

      $page = $request->page;
      $display_per_page = $request->display_per_page;
      $limit = ($page*$display_per_page)-$display_per_page;

      $purchases = $purchase->where('deleted',0);
      $purchases->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
      $num_items = $purchases->count();
      if($request->paging=="true"){
        $purchases->skip($limit);
        $purchases->take($display_per_page);
      }else{
        $display_per_page = $purchases->count();
      }
      $purchases = $purchases->get();

      foreach ($purchases as $purchase_data) {
        $purchase_data->total = $purchase_detail->select(DB::raw('SUM(cost_price*quantity) as total'))->where('purchase_id',$purchase_data->id)->value('total');
        $purchase_data->date_ = date("j-M",$purchase_data->date_);
      }
      $data["footer"]["total"] = $purchase_detail
        ->select(DB::raw('SUM(cost_price*quantity) as total'))
        ->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)])
        ->where('deleted',0)
        ->value('total');
      $data["paging"] = paging($page,$num_items,$display_per_page);
      $data["result"] = $purchases;
      $data["getQueryLog"] = DB::getQueryLog();
    }elseif ($type=="issuances") {
      DB::enableQueryLog();
      $issuance = new Issuance;
      $issuance_detail = new Issuance_detail;

      $page = $request->page;
      $display_per_page = $request->display_per_page;
      $limit = ($page*$display_per_page)-$display_per_page;

      $issuances = $issuance->where('deleted',0);
      $issuances->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
      $num_items = $issuances->count();
      if($request->paging=="true"){
        $issuances->skip($limit);
        $issuances->take($display_per_page);
      }else{
        $display_per_page = $issuances->count();
      }
      $issuances = $issuances->get();

      foreach ($issuances as $issuance_data) {
        $issuance_data->date_ = date("j-M",$issuance_data->date_);
      }
      $data["paging"] = paging($page,$num_items,$display_per_page);
      $data["result"] = $issuances;
      $data["getQueryLog"] = DB::getQueryLog();
    }
    return $data;
  }

}
