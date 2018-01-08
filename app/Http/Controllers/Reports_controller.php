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
use App\Restaurant_order;
use App\Restaurant_order_detail;
use Carbon\Carbon;

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
    $data["date_from"] = Carbon::parse($request->date_from);
    $data["date_to"] = Carbon::parse($request->date_to)->addDay()->subSecond();
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
      $app_config = DB::table('app_config')->first();
      $data["categories"] = explode(',', $app_config->categories);
      $data["restaurants"] = Restaurant::all();
      return view('reports.menu_popularity',$data);
    }else{
      return view('reports.issuances',$data);
    }
  }

  public function restaurant(Request $request)
  {
    $data["date_from"] = Carbon::createFromFormat('Y-m-d h:i:s',date('Y-m-d '.'00:00:00'));
    $data["date_to"] = Carbon::createFromFormat('Y-m-d h:i:s',date('Y-m-d '.'00:00:00'))->addDay()->subSecond();
    $app_config = DB::table('app_config')->first();
    $data["categories"] = explode(',', $app_config->categories);
    $data["settlements"] = explode(',', $app_config->settlements_arrangements);
    $data["restaurants"] = Restaurant::all();
    $data['restaurant_servers'] = Restaurant_server::withTrashed()->where('restaurant_id')->get();
    $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
    return view('reports.all',$data);
  }

  public function orders(Request $request)
  {
    $data = $this->get_orders_list($request);
    $data["date_from"] = date('F d, Y');
    $data["date_to"] = date('F d, Y');
    $user_data = $request->session()->get('users.user_data');
    if($user_data->privilege=='restaurant_cashier'){

    }elseif($user_data->privilege=='restaurant_admin'){
      $data['restaurant_servers'] = Restaurant_server::withTrashed()->get();
      $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
    }else{
      $data['restaurant_servers'] = Restaurant_server::withTrashed()->get();
      $data['restaurant_cashiers'] = User::withTrashed()->where('privilege','restaurant_cashier')->get();
    }
    // return $data;
    return view('reports.orders',$data);
  }

  public function get_orders_list(Request $request)
  {
    $orders = Restaurant_order::query();
    $orders->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id);
    if($request->server_id!=null){
      $orders->where('server_id',$request->server_id);
    }
    $orders->whereBetween('date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $data["result"] = $orders->paginate(50);
    foreach ($data['result'] as $order_data) {
      $order_data->date_ = date('j-M',$order_data->date_);
      $order_data->date_time = date('h:i:s A',$order_data->date_time);
      $order_data->que_number = sprintf('%04d',$order_data->que_number);
      $order_data->server_name = Restaurant_server::find($order_data->server_id)->name;
      $order_data->total = Restaurant_order_detail::select(DB::raw('SUM(quantity*price) as total'))->where('restaurant_order_id',$order_data->id)->value('total');
    }
    $data['pagination'] = (string)$data["result"];
    return $data;
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

  public function f_and_b_export(Request $request)
  {
    $app_config = DB::table('app_config')->first();
    $categories = $app_config->categories;
    $categories = explode(',', $categories);

    $settlements = $app_config->settlements.','.$app_config->badorder_settlements;
    $settlements = explode(',', $app_config->settlements_arrangements);
    foreach ($settlements as $settlement) {
      $settlements_headers[] = settlements($settlement);
    }
    $data = $this->f_and_b($request);
    $fp = fopen('assets/reports/order_slip_summary.csv', 'w');

    $headers = array ($request->session()->get('users.user_data')->restaurant.' Order Slip Summary Report');
    fputcsv($fp, $headers);
    $headers = array ('Date From: '.date('F d, Y',strtotime($request->date_from)).' Date To: '.date('F d, Y',strtotime($request->date_to)) );

    fputcsv($fp, $headers);
    $headers = array (
      'Date',
      'Outlet',
      'Check #',
      'Invoice #',
      'Guest Name',
      '# of Pax',
      '# of SC/PWD',
      'Server',
      'Cashier'
    );
    $headers = array_merge($headers,$categories);
    $initial_headers = array(
      'Gross Amount',
      'Total Discount',
      'NET Amount'
    );
    $headers = array_merge($headers,$initial_headers);
    $headers = array_merge($headers,$settlements_headers);

    $initial_headers = array('Total Settlements');
    $headers = array_merge($headers,$initial_headers);
    if($request->session()->get('users.user_data')->privilege!="admin"){

    }else{
      $initial_headers = array(
        'Special Discount',
        'Gross Billing',
        'SC/PWD Discount',
        'SC/PWD VAT Exemption',
        'NET Billing',
        'Sales NET of VAT & Service Charge',
        'Service Charge',
        'VATable Sales',
        'Output VAT',
        'Sales Inclusive of VAT',
      );
      $headers = array_merge($headers,$initial_headers);
    }

    fputcsv($fp, $headers);
    foreach ($data['result'] as $bill_data) {
      $headers = array (
        $bill_data['date_'],
        $bill_data['restaurant_name'],
        $bill_data['check_number'],
        $bill_data['invoice_number'],
        $bill_data['guest_name'],
        $bill_data['pax'],
        $bill_data['sc_pwd'],
        $bill_data['server_name'],
        $bill_data['cashier_name']
      );
      $categories_values = array();
      foreach ($categories as $key) {
        $categories_values[] = number_format($bill_data[$key],2);
      }
      $headers = array_merge($headers,$categories_values);
      $initial_headers = array(
        number_format($bill_data['total_item_amount'],2),
        number_format($bill_data['special_trade_discount'],2),
        number_format($bill_data['net_total_amount'],2)
      );
      $headers = array_merge($headers,$initial_headers);
      $settlements_values = array();
      foreach ($settlements as $key) {
        if($key=="cash"){
          $settlements_values[] = number_format($bill_data[$key]-$bill_data['excess'],2);
        }else{
          $settlements_values[] = number_format($bill_data[$key],2);
        }
      }
      $headers = array_merge($headers,$settlements_values);

      $initial_headers = array(number_format($bill_data['total_settlements'],2));
      $headers = array_merge($headers,$initial_headers);
      if($request->session()->get('users.user_data')->privilege!="admin"){

      }else{
        $initial_headers = array(
          number_format($bill_data['total_discount'],2),
          number_format($bill_data['gross_billing'],2),
          number_format($bill_data['sc_pwd_discount'],2),
          number_format($bill_data['sc_pwd_vat_exemption'],2),
          number_format($bill_data['net_billing'],2),
          number_format($bill_data['sales_net_of_vat_and_service_charge'],2),
          number_format($bill_data['service_charge'],2),
          number_format($bill_data['vatable_sales'],2),
          number_format($bill_data['output_vat'],2),
          number_format($bill_data['sales_inclusive_of_vat'],2),
        );
        $headers = array_merge($headers,$initial_headers);
      }
      fputcsv($fp, $headers);
    }


    //Footer

    $headers = array (
      "",
      "",
      "",
      "",
      "",
      $data['footer']['pax'],
      $data['footer']['sc_pwd'],
      "",
      ""
    );
    $categories_values = array();
    foreach ($categories as $key) {
      $categories_values[] = number_format($data['footer'][$key],2);
    }
    $headers = array_merge($headers,$categories_values);
    $initial_headers = array(
      number_format($data['footer']['total_item_amount'],2),
      number_format($data['footer']['special_trade_discount'],2),
      number_format($data['footer']['net_total_amount'],2)
    );
    $headers = array_merge($headers,$initial_headers);
    $settlements_values = array();
    foreach ($settlements as $key) {
      if($key=="cash"){
        $settlements_values[] = number_format($data['footer'][$key]-$data['footer']['excess'],2);
      }else{
        $settlements_values[] = number_format($data['footer'][$key],2);
      }
    }
    $headers = array_merge($headers,$settlements_values);

    $initial_headers = array(number_format($data['footer']['total_settlements'],2));
    $headers = array_merge($headers,$initial_headers);
    if($request->session()->get('users.user_data')->privilege!="admin"){

    }else{
      $initial_headers = array(
        number_format($data['footer']['total_discount'],2),
        number_format($data['footer']['gross_billing'],2),
        number_format($data['footer']['sc_pwd_discount'],2),
        number_format($data['footer']['sc_pwd_vat_exemption'],2),
        number_format($data['footer']['net_billing'],2),
        number_format($data['footer']['sales_net_of_vat_and_service_charge'],2),
        number_format($data['footer']['service_charge'],2),
        number_format($data['footer']['vatable_sales'],2),
        number_format($data['footer']['output_vat'],2),
        number_format($data['footer']['sales_inclusive_of_vat'],2),
      );
      $headers = array_merge($headers,$initial_headers);
    }
      fputcsv($fp, $headers);  
    


    fclose($fp);
    return asset('assets/reports/order_slip_summary.csv');
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
    if($request->meal_type!=null||$request->meal_type!=""){
      $bills->where('meal_type',$request->meal_type);
    }
    if($user_data->privilege!='admin'){
      $bills->where('restaurant_id',$user_data->restaurant_id);
    }elseif($request->restaurant_id!=null){
      $bills->where('restaurant_id',$request->restaurant_id);
    }
    $bills->whereBetween('created_at',[Carbon::parse($request->date_from),Carbon::parse($request->date_to)]);
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
    $footer_data->where('type','good_order');
    $footer_data->whereBetween('created_at',[Carbon::parse($request->date_from),Carbon::parse($request->date_to)]);
    if($user_data->privilege=='restaurant_cashier'){
      $footer_data->where('cashier_id',$user_data->id);
    }
    if($request->server_id!=null){
      $footer_data->where('server_id',$request->server_id);
    }
    if($request->cashier_id!=null){
      $footer_data->where('cashier_id',$request->cashier_id);
    }
    if($request->meal_type!=null||$request->meal_type!=""){
      $footer_data->where('meal_type',$request->meal_type);
    }
    if($user_data->privilege!='admin'){
      $footer_data->where('restaurant_id',$user_data->restaurant_id);
    }elseif($request->restaurant_id!=null){
      $footer_data->where('restaurant_id',$request->restaurant_id);
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
      $category_total->whereBetween('restaurant_bill.created_at',[Carbon::parse($request->date_from),Carbon::parse($request->date_to)]);
      $category_total->select(
          'restaurant_bill.*',
          'restaurant_bill_detail.*',
          'restaurant_menu.category',
          DB::raw('SUM(restaurant_bill_detail.price*restaurant_bill_detail.quantity) as total')
          );
      $category_total->where('restaurant_menu.category',$category);
      $category_total->where('restaurant_bill.deleted',0);
      $category_total->where('restaurant_bill.type','good_order');
      if($user_data->privilege=='restaurant_cashier'){
        $category_total->where('cashier_id',$user_data->id);
      }
      if($request->server_id!=null){
        $category_total->where('server_id',$request->server_id);
      }
      if($request->cashier_id!=null){
        $category_total->where('cashier_id',$request->cashier_id);
      }
      if($request->meal_type!=null||$request->meal_type!=""){
        $category_total->where('restaurant_bill.meal_type',$request->meal_type);
      }
      if($user_data->privilege!='admin'){
        $category_total->where('restaurant_bill.restaurant_id',$user_data->restaurant_id);
      }elseif($request->restaurant_id!=null){
        $category_total->where('restaurant_bill.restaurant_id',$request->restaurant_id);
      }

      $data["footer"][$category] = $category_total->value('total');
      $data["footer"]["total_item_amount"] += $category_total->value('total');
    }
    $data['footer']['total_settlements'] = 0;
    foreach ($settlements as $settlement) {
      $settlement_total = $restaurant_bill->join('restaurant_payment','restaurant_bill.id','=','restaurant_payment.restaurant_bill_id');
      $settlement_total->where('restaurant_bill.deleted',0);
      $settlement_total->whereBetween('restaurant_payment.created_at',[Carbon::parse($request->date_from),Carbon::parse($request->date_to)]);
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
      if($request->meal_type!=null||$request->meal_type!=""){
        $settlement_total->where('restaurant_bill.meal_type',$request->meal_type);
      }
      if($user_data->privilege!='admin'){
        $settlement_total->where('restaurant_bill.restaurant_id',$user_data->restaurant_id);
      }elseif($request->restaurant_id!=null){
        $settlement_total->where('restaurant_bill.restaurant_id',$request->restaurant_id);
      }


      if($settlement=="cash"){
        $data["footer"][$settlement] = $settlement_total->value('total')-$data["footer"]["excess"];
        $data['footer']['total_settlements'] += $data["footer"][$settlement];
      }elseif($settlement=="cancelled"){
        $data["footer"][$settlement] = $settlement_total->value('total');
        // $data['footer']['total_settlements'] -= $data["footer"][$settlement];
      }else{
        $data["footer"][$settlement] = $settlement_total->value('total');
        $data['footer']['total_settlements'] += $data["footer"][$settlement];
      }
    }
    $data["footer"]["special_trade_discount"] = $data['footer']['total_discount']+$data['footer']['sc_pwd_discount']+$data['footer']['sc_pwd_vat_exemption'];
    $data["footer"]["net_total_amount"] = $data["footer"]["total_item_amount"]-$data["footer"]["special_trade_discount"];

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
        $bill_data->$category = $restaurant_bill_detail->query();
        $bill_data->$category->join('restaurant_bill','restaurant_bill_detail.restaurant_bill_id','=','restaurant_bill.id');
        $bill_data->$category->join('restaurant_menu','restaurant_bill_detail.restaurant_menu_id','=','restaurant_menu.id');
        $bill_data->$category->where('restaurant_bill_id',$bill_data->id);
        $bill_data->$category->where('category',$category);
        $bill_data->$category->where('type','good_order');
        $bill_data->$category->select('restaurant_bill_detail.*','restaurant_bill.type','restaurant_menu.category',DB::raw('SUM(restaurant_bill_detail.price*quantity) as total'));
        $bill_data->$category = $bill_data->$category->value('total');
        $bill_data->total += $bill_data->$category;
      }
      foreach ($settlements as $settlement) {
        if($settlement!='cancelled'){
          $bill_data->$settlement = $restaurant_payment->where(['restaurant_bill_id'=>$bill_data->id,'settlement'=>$settlement])->value('payment');
          $bill_data->total_settlements += $bill_data->$settlement;
        }else{
          $bill_data->$settlement = $restaurant_payment->where(['restaurant_bill_id'=>$bill_data->id,'settlement'=>$settlement])->value('payment');
          
        }
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

    $restaurant_bill_detail = new Restaurant_bill_detail;
    $menu_popularity = $restaurant_bill_detail->where('restaurant_bill_detail.deleted',0);
    $menu_popularity->select('restaurant_menu.*',DB::raw('SUM(quantity) as total_quantity'));
    $menu_popularity->whereBetween('restaurant_bill_detail.date_',[strtotime($request->date_from),strtotime($request->date_to)]);
    $menu_popularity->join('restaurant_menu','restaurant_menu.id','=','restaurant_menu_id');
    if($request->restaurant_id!=null){
      $menu_popularity->where('restaurant_menu.restaurant_id',$request->restaurant_id);
    }
    if($request->category!=null&&$request->category!='all'){
      $menu_popularity->where('restaurant_menu.category',$request->category);
    }
    if($request->subcategory!=null&&$request->subcategory!='all'){
      $menu_popularity->where('restaurant_menu.subcategory',$request->subcategory);
    }
    $menu_popularity->groupBy('restaurant_menu_id');
    $menu_popularity->orderBy('total_quantity', 'DESC');
    if($request->export=='1'){
      $count = count($menu_popularity->get());
      $data["result"] = $menu_popularity->paginate($count);
    }else{
      $data["result"] = $menu_popularity->paginate(50);
    }
    $data['pagination'] = (string)$data["result"];
    $data["getQueryLog"] = DB::getQueryLog();

    return $data;
  }

  public function menu_popularity_export(Request $request)
  {
    $data = $this->menu_popularity($request);
    $data = $data['result'];

    $fp = fopen('assets/reports/menu_popularity_report.csv', 'w');

    $headers = array ($request->restaurant_name.' Menu Popularity Report in');
    fputcsv($fp, $headers);
    $headers = array ('Date From: '.date('F d, Y',strtotime($request->date_from)).' Date To: '.date('F d, Y',strtotime($request->date_to)) );
    fputcsv($fp, $headers);
    $headers = array(
      'Category',
      'Subcategory',
      'Menu',
      'Served Quantity',
      'Total Amount'
    );
    fputcsv($fp, $headers);
    // var_dump($data);
    foreach ($data as $value) {
      $fields = array();
      $fields[] = $value['category'];
      $fields[] = $value['subcategory'];
      $fields[] = $value['name'];
      $fields[] = $value['total_quantity'];
      $fields[] = $value['total_quantity']*$value['price'];
      fputcsv($fp, $fields);
    }

    fclose($fp);
    return asset('assets/reports/menu_popularity_report.csv');
  }

}
