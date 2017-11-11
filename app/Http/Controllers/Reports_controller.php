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
use App\Restaurant_server;
use App\User;
use App\Restaurant;

class Reports_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
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
      $data["settlements"] = explode(',', $app_config->settlements_arrangements);
      $data["restaurants"] = Restaurant::all();
      $user_data = $request->session()->get('users.user_data');
      if($user_data->privilege=='restaurant_cashier'){

      }elseif($user_data->privilege=='restaurant_admin'){
        $data['restaurant_servers'] = Restaurant_server::withTrashed()->get();
        $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
      }else{
        $data['restaurant_servers'] = Restaurant_server::withTrashed()->get();
        $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
      }
      return view('reports.all',$data);
    }elseif ($type=="purchases") {
      # code...
      return view('reports.purchases',$data);
    }elseif ($type=="menu_popularity") {
      # code...
      return view('reports.menu_popularity',$data);
    }else{
      return view('reports.issuances',$data);
    }
  }

  public function restaurant(Request $request)
  {
    $data["date_from"] = date('F d, Y');
    $data["date_to"] = date('F d, Y');
    $app_config = DB::table('app_config')->first();
    $data["categories"] = explode(',', $app_config->categories);
    $data["settlements"] = explode(',', $app_config->settlements_arrangements);
    $data["restaurants"] = Restaurant::all();
    $data['restaurant_servers'] = Restaurant_server::withTrashed()->where('restaurant_id')->get();
    $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
    return view('reports.all',$data);
  }

  public function restaurant_print(Request $request)
  {
    $app_config = DB::table('app_config')->first();
    $data["categories"] = explode(',', $app_config->categories);
    $data["settlements"] = explode(',', $app_config->settlements);
    $data["date_from"] = date('F d, Y');
    $data["date_to"] = date('F d, Y');
    return view('reports.printable.all',$data);
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
  public function f_and_b(Request $request)
  {
    
    DB::enableQueryLog();
    
    $app_config = DB::table('app_config')->first();
    $categories = $app_config->categories;
    $categories = explode(',', $categories);

    $settlements = $app_config->settlements.','.$app_config->badorder_settlements;
    $settlements = explode(',', $app_config->settlements_arrangements);

    $page = $request->page;
    $display_per_page = $request->display_per_page;
    $limit = ($page*$display_per_page)-$display_per_page;

    $user_data = $request->session()->get('users.user_data');
    // $data['user_data'] = $user_data;

    $restaurant_bill = new Restaurant_bill;
    $restaurant_bill_detail = new Restaurant_bill_detail;
    $restaurant_payment = new Restaurant_payment;
    $bills = $restaurant_bill->where('deleted',0);
    if($user_data->privilege=='restaurant_cashier'){
      $bills->where('cashier_id',$user_data->id);
    }
    if($request->server_id!=null){
      $bills->where('server_id',$request->server_id);
    }
    if($request->cashier_id!=null){
      $bills->where('cashier_id',$request->cashier_id);
    }
    if($user_data->privilege!='admin'){
      $bills->where('restaurant_id',$user_data->restaurant_id);
    }
    $bills->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $num_items = $bills->count();
    if($request->paging=="true"){
      $bills->skip($limit);
      $bills->take($display_per_page);
    }else{
      $display_per_page = $bills->count();
    }
    $bills = $bills->get();

    $footer_data = $restaurant_bill->select(
      '*',
      DB::raw('SUM(pax) as total_pax'),
      DB::raw('SUM(excess) as total_excess'),
      DB::raw('SUM(discounts) as total_discounts'),
      DB::raw('SUM(gross_billing) as total_gross_billing'),
      DB::raw('SUM(sc_pwd_discount) as total_sc_pwd_discount'),
      DB::raw('SUM(sc_pwd) as total_sc_pwd'),
      DB::raw('SUM(sc_pwd_vat_exemption) as total_sc_pwd_vat_exemption'),
      DB::raw('SUM(total_discount) as total_total_discount'),
      DB::raw('SUM(net_billing) as total_net_billing'),
      DB::raw('SUM(sales_net_of_vat_and_service_charge) as total_sales_net_of_vat_and_service_charge'),
      DB::raw('SUM(service_charge) as total_service_charge'),
      DB::raw('SUM(vatable_sales) as total_vatable_sales'),
      DB::raw('SUM(output_vat) as total_output_vat'),
      DB::raw('SUM(sales_inclusive_of_vat) as total_sales_inclusive_of_vat')
    );
    $footer_data->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    if($user_data->privilege=='restaurant_cashier'){
      $footer_data->where('cashier_id',$user_data->id);
    }

    if($request->server_id!=null){
      $footer_data->where('server_id',$request->server_id);
    }
    if($request->cashier_id!=null){
      $footer_data->where('cashier_id',$request->cashier_id);
    }
    if($user_data->privilege!='admin'){
      $footer_data->where('restaurant_id',$user_data->restaurant_id);
    }

    $footer_data = $footer_data->first();
    $data['footer']['pax'] = $footer_data->total_pax;
    $data['footer']['excess'] = $footer_data->total_excess;
    $data['footer']['discounts'] = $footer_data->total_discounts;
    $data['footer']['gross_billing'] = $footer_data->total_gross_billing;
    $data['footer']['sc_pwd_discount'] = $footer_data->total_sc_pwd_discount;
    $data['footer']['sc_pwd_vat_exemption'] = $footer_data->total_sc_pwd_vat_exemption;
    $data['footer']['total_discount'] = $footer_data->total_total_discount;
    $data['footer']['net_billing'] = $footer_data->total_net_billing;
    $data['footer']['sales_net_of_vat_and_service_charge'] = $footer_data->total_sales_net_of_vat_and_service_charge;
    $data['footer']['service_charge'] = $footer_data->total_service_charge;
    $data['footer']['vatable_sales'] = $footer_data->total_vatable_sales;
    $data['footer']['output_vat'] = $footer_data->total_output_vat;
    $data['footer']['sales_inclusive_of_vat'] = $footer_data->total_sales_inclusive_of_vat;
    $data['footer']['sc_pwd'] = $footer_data->total_sc_pwd;

    $data["footer"]["total_item_amount"] = 0;
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
      if($user_data->privilege=='restaurant_cashier'){
        $category_total->where('cashier_id',$user_data->id);
      }
      if($request->server_id!=null){
        $category_total->where('server_id',$request->server_id);
      }
      if($request->cashier_id!=null){
        $category_total->where('cashier_id',$request->cashier_id);
      }
      if($user_data->privilege!='admin'){
        $category_total->where('restaurant_bill.restaurant_id',$user_data->restaurant_id);
      }

      $data["footer"][$category] = $category_total->value('total');
      $data["footer"]["total_item_amount"] += $category_total->value('total');
    }
    $data['footer']['total_settlements'] = 0;
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

      if($user_data->privilege=='restaurant_cashier'){
        $settlement_total->where('restaurant_bill.cashier_id',$user_data->id);
      }
      if($request->server_id!=null){
        $settlement_total->where('restaurant_bill.server_id',$request->server_id);
      }
      if($request->cashier_id!=null){
        $settlement_total->where('restaurant_bill.cashier_id',$request->cashier_id);
      }
      if($user_data->privilege!='admin'){
        $settlement_total->where('restaurant_bill.restaurant_id',$user_data->restaurant_id);
      }


      if($settlement=="cash"){
        $data["footer"][$settlement] = $settlement_total->value('total')-$data["footer"]["excess"];
      }else{
        $data["footer"][$settlement] = $settlement_total->value('total');
      }
      $data['footer']['total_settlements'] += $data["footer"][$settlement];
    }
    $data["footer"]["special_trade_discount"] = $data['footer']['total_discount']+$data['footer']['sc_pwd_discount']+$data['footer']['sc_pwd_vat_exemption'];
    $data["footer"]["net_total_amount"] = $data["footer"]["total_item_amount"]-$data["footer"]["special_trade_discount"];
    $data["paging"] = paging($page,$num_items,$display_per_page);

    foreach ($bills as $bill_data) {
      $bill_data->date_time = date("h:i:s A",$bill_data->date_time);
      $bill_data->date_ = date("j-M",$bill_data->date_);
      $bill_data->check_number = sprintf('%04d',$bill_data->check_number);
      $bill_data->total_settlements = 0;
      $bill_data->special_trade_discount = $bill_data->total_discount+$bill_data->sc_pwd_discount+$bill_data->sc_pwd_vat_exemption;
      $bill_data->net_total_amount = $bill_data->total_item_amount-$bill_data->special_trade_discount;
      $restaurant_server = new Restaurant_server;
      $bill_data->server_name = $restaurant_server->withTrashed()->find($bill_data->server_id)->name;

      $user = new User;
      $bill_data->cashier_name = $user->withTrashed()->find($bill_data->cashier_id)->name;

      $restaurant = new Restaurant;
      $bill_data->restaurant_name = $restaurant->find($bill_data->restaurant_id)->name;
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
        $bill_data->total_settlements += $bill_data->$settlement;
      }
      $bill_data->total_settlements -= $bill_data->excess;

    }
    $data["result"] = $bills;
    $data["getQueryLog"] = DB::getQueryLog();
    return $data;
  }

  public function purhcased_item(Request $request)
  {

    DB::enableQueryLog();
    $purchase = new Purchase;
    $purchase_detail = new Purchase_detail;

    $page = $request->page;
    $display_per_page = $request->display_per_page;
    $limit = ($page*$display_per_page)-$display_per_page;

    $purchase_detail_item = $purchase_detail->where('deleted',0);
    $purchase_detail_item->select('inventory_item_id')->distinct();
    $purchase_detail_item->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $num_items = $purchase_detail_item->count();
    if($request->paging=="true"){
      $purchase_detail_item->skip($limit);
      $purchase_detail_item->take($display_per_page);
    }else{
      $display_per_page = $purchase_detail_item->count();
    }
    $purchase_detail_item = $purchase_detail_item->get();

    $data["footer"]["total_cost"] = 0;
    foreach ($purchase_detail_item as $item) {
      $item_cost = $purchase_detail->where('purchase_detail.inventory_item_id',$item->inventory_item_id);
      $item_cost->where('purchase_detail.deleted',0);
      $item_cost->select('inventory_item.item_name',DB::raw('SUM(purchase_detail.quantity*purchase_detail.cost_price) as total_cost'),DB::raw('SUM(purchase_detail.quantity) as total_quantity'));
      $item_cost->whereBetween('purchase_detail.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
      $item_cost->join('inventory_item', 'inventory_item.id', '=', 'purchase_detail.inventory_item_id');
      $item->total_cost = $item_cost->value('total_cost');
      $item->total_quantity = $item_cost->value('total_quantity');
      $item->item_name = $item_cost->value('item_name');
      $data["footer"]["total_cost"] += $item->total_cost;
    }
    $data["paging"] = paging($page,$num_items,$display_per_page);
    $data["result"] = $purchase_detail_item;
    $data["getQueryLog"] = DB::getQueryLog();

    return $data;
  }

  public function issued_items(Request $request)
  {

    DB::enableQueryLog();
    $issuance = new Issuance;
    $issuance_detail = new Issuance_detail;

    $page = $request->page;
    $display_per_page = $request->display_per_page;
    $limit = ($page*$display_per_page)-$display_per_page;

    $issued_items = $issuance_detail->where('issuance.deleted',0);
    $issued_items->whereBetween('issuance.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $issued_items->join('issuance','issuance.id','=','issuance_detail.issuance_id');
    $issued_items->join('inventory_item','inventory_item.id','=','issuance_detail.inventory_item_id');
    $issued_items->join('issuance_to','issuance_to.id','=','issuance.issuance_to');
    $issued_items->select(
        'issuance_detail.*',
        'issuance.issuance_number',
        'issuance.comments',
        'inventory_item.item_name',
        'issuance_to.name as issuance_to'
      );
    $num_items = $issued_items->count();
    if($request->paging=="true"){
      $issued_items->skip($limit);
      $issued_items->take($display_per_page);
    }else{
      $display_per_page = $issued_items->count();
    }
    $issued_items = $issued_items->get();

    foreach ($issued_items as $issuance_data) {
      $issuance_data->date_ = date("j-M",$issuance_data->date_);
    }
    // $data["paging"] = paging($page,$num_items,$display_per_page);
    $data["result"] = $issued_items;
    $data["getQueryLog"] = DB::getQueryLog();
    return $data;
  }

  public function menu_popularity(Request $request)
  {
    DB::enableQueryLog();
    $page = $request->page;
    $display_per_page = $request->display_per_page;
    $limit = ($page*$display_per_page)-$display_per_page;

    $restaurant_bill_detail = new Restaurant_bill_detail;
    $menu_popularity = $restaurant_bill_detail->where('restaurant_bill_detail.deleted',0);
    $menu_popularity->select('restaurant_menu.*',DB::raw('SUM(quantity) as total_quantity'));
    $menu_popularity->whereBetween('restaurant_bill_detail.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $menu_popularity->join('restaurant_menu','restaurant_menu.id','=','restaurant_menu_id');
    $menu_popularity->groupBy('restaurant_menu_id');

    $num_items = $menu_popularity->count();
    if($request->paging=="true"){
      $menu_popularity->skip($limit);
      $menu_popularity->take($display_per_page);
    }else{
      $display_per_page = $menu_popularity->count();
    }

    $data["result"] = $menu_popularity->orderBy('total_quantity', 'DESC')->get();
    $data["paging"] = paging($page,$num_items,$display_per_page);
    $data["getQueryLog"] = DB::getQueryLog();
    return $data;
  }

}
