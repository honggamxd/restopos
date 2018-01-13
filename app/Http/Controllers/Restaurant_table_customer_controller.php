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
use App\Restaurant_temp_bill;
use App\Restaurant_temp_bill_detail;
use App\Restaurant_bill;
use App\Restaurant_bill_detail;
use App\Restaurant_accepted_order_cancellation;
use App\Restaurant_order_cancellation;
use Carbon\Carbon;

class Restaurant_table_customer_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function store(Request $request)
  {
    $this->validate($request, [
        'table_id.id' => 'required',
        'server_id.id' => 'required',
        'pax' => 'integer|required|custom_min:1',
        'sc_pwd' => 'integer|required|custom_max:'.$request->pax,
    ],[
      'custom_min' => 'The number of :attribute must be greater than or equal to 1.',
      'server_id.id.required' => 'The Server field is required.',
      'sc_pwd.custom_max' => 'The number of SC/PWD must not be greater than '.$request->pax.' pax.',
    ]);
    DB::beginTransaction();
    try{
        
        $restaurant_table_customer = new Restaurant_table_customer;
        $restaurant_table_customer->restaurant_table_id = $request->table_id["id"];
        $restaurant_table_customer->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;;
        $restaurant_table_customer->table_name = $request->table_id["name"];
        $restaurant_table_customer->guest_name = ($request->guest_name==null?"":$request->guest_name);
        $restaurant_table_customer->pax = $request->pax;
        $restaurant_table_customer->sc_pwd = $request->sc_pwd;
        $restaurant_table_customer->server_id = $request->server_id["id"];
        $restaurant_table_customer->date_time = strtotime(date("m/d/Y h:i:s A"));
        $restaurant_table_customer->save();
        $restaurant_table = new Restaurant_table;
        $table_occupied = $restaurant_table->find($request->table_id["id"]);
        $table_occupied->occupied = 1;
        $table_occupied->save();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
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
        $data["cart"]["menu_".$request->menu_id]->special_instruction = "";
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

  public function update_cart_item(Request $request,$type,$id)
  {
    if($type=="special_instruction"){
      $cart_data = $request->session()->get('restaurant.table_customer.'.$id.".cart");
      $cart_data["menu_".$request->menu_id]->special_instruction = $request->special_instruction;
      $request->session()->put('restaurant.table_customer.'.$id.".cart",$cart_data);
      return $this->show($request,$id);
    }elseif ($type=="quantity") {
      if($request->quantity != 0){
        $cart_data = $request->session()->get('restaurant.table_customer.'.$id.".cart");
        $cart_data["menu_".$request->menu_id]->quantity = abs($request->quantity);
        $cart_data["menu_".$request->menu_id]->total = $cart_data["menu_".$request->menu_id]->quantity * $cart_data["menu_".$request->menu_id]->price;
        $request->session()->put('restaurant.table_customer.'.$id.".cart",$cart_data);
      }
      return $this->show($request,$id);
    }
  }

  public function remove_cart_item(Request $request)
  {
    if($request->session()->has('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id)&&$request->session()->get('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id)!=array()){
      $request->session()->forget('restaurant.table_customer.'.$request->table_customer_id.'.cart.menu_'.$request->menu_id);
    }
    return $this->show($request,$request->table_customer_id);
    // if($request->session()->has())
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
    $data["result"] = $restaurant_table_customer->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)->get();
    foreach ($data["result"] as $customer_data) {
      $customer_data->time = date("h:i:s A",strtotime($customer_data->created_at));
      $customer_data->date = date("m/d/Y",strtotime($customer_data->created_at));
      $customer_data->has_order = ($customer_data->has_order==1?TRUE:FALSE);
      $customer_data->has_billed_out = ($customer_data->has_billed_out==1?TRUE:FALSE);
      $customer_data->has_bill = ($customer_data->has_bill==1?TRUE:FALSE);
      $customer_data->server_name = DB::table('restaurant_server')->find($customer_data->server_id)->name;
      $customer_data->total = $restaurant_order
        ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
        ->select( DB::raw('SUM(quantity*price) as total'))
        ->where("restaurant_table_customer_id",$customer_data->id)
        ->first()
        ->total;
      $customer_data->table_data = $restaurant_table->find($customer_data->restaurant_table_id);
    }
    return $data;
  }

  public function bill_out(Request $request,$id)
  {
    DB::beginTransaction();
    try{
        $restaurant_temp_bill = new Restaurant_temp_bill;
        $restaurant_order = new Restaurant_order;
        $unique_ordered_items = $restaurant_order
          ->select('restaurant_menu_id')
          ->distinct()
          ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
          ->where('restaurant_table_customer_id',$id)
          ->get();
        $restaurant_temp_bill->restaurant_table_customer_id = $id;
        $restaurant_temp_bill->save();
        $temp_bill_data = $restaurant_temp_bill->orderBy('id','DESC')->first();

        $restaurant_table_customer = new Restaurant_table_customer;
        $customer_data = $restaurant_table_customer->find($id);
        $customer_data->has_billed_out = 1;
        $customer_data->restaurant_temp_bill_id = $temp_bill_data->id;
        $customer_data->save();

        foreach ($unique_ordered_items as $order_item_data) {
          $order_joined_data = $restaurant_order
            ->select('restaurant_menu_name','special_instruction','restaurant_menu_id','price',DB::raw('SUM(quantity) as total_quantity'))
            ->join('restaurant_order_detail', 'restaurant_order.id', '=', 'restaurant_order_detail.restaurant_order_id')
            ->where('restaurant_table_customer_id',$id)
            ->where('restaurant_menu_id',$order_item_data->restaurant_menu_id)
            ->first();
          $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
          $restaurant_temp_bill_detail->restaurant_menu_id = $order_joined_data->restaurant_menu_id;
          $restaurant_temp_bill_detail->restaurant_menu_name = $order_joined_data->restaurant_menu_name;
          $restaurant_temp_bill_detail->price = $order_joined_data->price;
          $restaurant_temp_bill_detail->quantity = $order_joined_data->total_quantity;
          $restaurant_temp_bill_detail->special_instruction = $order_joined_data->special_instruction;
          $restaurant_temp_bill_detail->restaurant_temp_bill_id = $temp_bill_data->id;
          $restaurant_temp_bill_detail->save();
        }
        $data["result"] = $restaurant_temp_bill
          ->join('restaurant_temp_bill_detail','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
          ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
          ->where('restaurant_table_customer_id',$id)
          ->get();

        $temp_bill_remaining_quantity = $restaurant_temp_bill_detail
        ->join('restaurant_temp_bill','restaurant_temp_bill.id','=','restaurant_temp_bill_detail.restaurant_temp_bill_id')
        ->select('restaurant_temp_bill.*',DB::raw('SUM(restaurant_temp_bill_detail.quantity) as total'))
        ->where('restaurant_temp_bill.restaurant_table_customer_id',$id)
        ->value('total');

        $restaurant_table_customer = new Restaurant_table_customer;
        $customer_data = $restaurant_table_customer->find($id);
        if($temp_bill_remaining_quantity==0){
          $customer_data->cancellation_order_status = 0;
          $customer_data->has_billed_completely = 1;
          $customer_data->has_paid = 1;
        }
        $customer_data->save();


        $restaurant_order_cancellation = new Restaurant_order_cancellation;
        $restaurant_order_cancellation_data = $restaurant_order_cancellation->where('approved',0)->where('restaurant_table_customer_id',$id);
        if($restaurant_order_cancellation_data->get()!=array()){
          $customer_data->has_cancellation_request = 0;
          $customer_data->save();
          $restaurant_order_cancellation_data->delete();
        }
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $this->show_temp_bill($request,$id);
  }

  public function show_temp_bill(Request $request,$id)
  {
    $restaurant_temp_bill = new Restaurant_temp_bill;
    $data["result"] = $restaurant_temp_bill
      ->join('restaurant_temp_bill_detail', 'restaurant_temp_bill.id', '=', 'restaurant_temp_bill_detail.restaurant_temp_bill_id')
      ->join('restaurant_menu','restaurant_menu.id','=','restaurant_temp_bill_detail.restaurant_menu_id')
      ->where('restaurant_table_customer_id',$id)
      ->get();
    $restaurant_temp_bill_data = $restaurant_temp_bill->where('restaurant_table_customer_id',$id)->first();
    $restaurant_temp_bill_detail = new Restaurant_temp_bill_detail;
    $restaurant_table_customer = new Restaurant_table_customer;
    $data['customer_data'] = $restaurant_table_customer->find($id);
    $data["total"] = $restaurant_temp_bill_detail
      ->select(DB::raw('SUM(price*quantity) as total'))
      ->where('restaurant_temp_bill_id',$restaurant_temp_bill_data->id)
      ->value("total");
    $data['discount']['amount_disount'] = 0;
    $data['discount']['percent_disount'] = 0;
    $data['discount']['total'] = 0;
    $data['discount']['room_service_charge'] = 0;
    $data['discount']['sc_pwd_discount'] = $data["total"]*$data['customer_data']->sc_pwd/$data['customer_data']->pax/1.12*0.2;
    $data['discount']['sc_pwd_vat_exemption'] = $data["total"]*$data['customer_data']->sc_pwd/$data['customer_data']->pax/1.12*0.12;
    $data['gross_billing'] = $data["total"];
    $data['net_billing'] = round($data["total"]-$data['discount']['sc_pwd_discount']-$data['discount']['sc_pwd_vat_exemption'],2);
    return $data;
  }

  public function show_order(Request $request,$id)
  {
    $restaurant_order = new Restaurant_order;
    $data["result"] = $restaurant_order->where('restaurant_table_customer_id',$id)->get();
    foreach ($data["result"] as $restaurant_order_data) {
      $restaurant_order_data->format_id = sprintf('%04d',$restaurant_order_data->id);
      $restaurant_order_data->que_number = sprintf('%04d',$restaurant_order_data->que_number);
    }
    $restaurant_table_customer = new Restaurant_table_customer;
    $data['customer_data'] = $restaurant_table_customer->find($id);
    return $data;
  }

  public function destroy(Request $request,$id)
  {
    $restaurant_table_customer = new Restaurant_table_customer;
    $restaurant_table = new Restaurant_table;
    $customer_data = $restaurant_table_customer->find($id);

    $customers = $restaurant_table_customer->where('restaurant_table_id',$customer_data->restaurant_table_id);
    // return $customers->count();
    if($customers->count()==1){
      DB::beginTransaction();
      try{  
          $table_data = $restaurant_table->where("id",$customer_data->restaurant_table_id)->first();
          $table_data->occupied = 0;
          $table_data->save();
          DB::commit();
      }
      catch(\Exception $e){DB::rollback();throw $e;}
    }
    $restaurant_table_customer->find($id)->delete();
  }

  
  public function delete(Request $request)
  {
    $request->session()->forget('restaurant');
    # code...
  }

  public function update(Request $request,$id)
  {
    // return $request->customer_data['table_data'];
    // exit;
    $this->validate($request, [
        'pax' => 'integer|required|custom_min:1',
        'sc_pwd' => 'integer|required|custom_max:'.$request->pax,
    ],[
      'custom_min' => 'The number of :attribute must be greater than or equal to 1.',
      'sc_pwd.custom_max' => 'The number of SC/PWD must not be greater than '.$request->pax.' pax.',
    ]);
    DB::beginTransaction();
    try{
        $restaurant_table_customer = new Restaurant_table_customer;
        $data["new_table"] = $request->table_id;
        $data["old_table"] = $request->customer_data['table_data'];
        $customer_data = $restaurant_table_customer->find($id);
        $customer_data->pax = $request->pax;
        $customer_data->sc_pwd = $request->sc_pwd;
        $customer_data->guest_name = $request->guest_name;
        if($data["new_table"]['id']!=$data["old_table"]['id']){
          // $customer_data->table_name .= '>'.$data["new_table"]['name'];
          $customer_data->table_name = $data["new_table"]['name'];
          $customer_data->restaurant_table_id = $data["new_table"]['id'];
        }
        $customer_data->save();
        $customer_data = $restaurant_table_customer->find($id);
        $customers = $restaurant_table_customer->where('restaurant_table_id',$data["old_table"]['id']);
        if($customers->count()==0){
          $restaurant_table = new Restaurant_table;
          $table_data = $restaurant_table->find($data["old_table"]['id']);
          $table_data->occupied = 0;
          $table_data->save();
        }else{
          $restaurant_table = new Restaurant_table;
          $table_data = $restaurant_table->find($data["old_table"]['id']);
          $table_data->occupied = 1;
          $table_data->save();
        }

        $customers = $restaurant_table_customer->where('restaurant_table_id',$data["new_table"]['id']);
        if($customers->count()==0){
          $restaurant_table = new Restaurant_table;
          $table_data = $restaurant_table->find($data["new_table"]['id']);
          $table_data->occupied = 0;
          $table_data->save();
        }else{
          $restaurant_table = new Restaurant_table;
          $table_data = $restaurant_table->find($data["new_table"]['id']);
          $table_data->occupied = 1;
          $table_data->save();
        }
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $data;
  }
}
