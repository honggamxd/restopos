<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Issuance;
use App\Issuance_detail;
use App\Inventory_item;
use App\Inventory_item_detail;
use App\Restaurant_inventory;

class Issuance_controller extends Controller
{
  public function index(Request $request)
  {
    $data["issuance_to"] = DB::table('issuance_to')->where('deleted',0)->get();
    return view('issuance.index',$data);
  }
  public function show(Request $request,$id)
  {
    $issuance = new Issuance;
    $issuance_detail = new Issuance_detail;
    $data = array();
    if($issuance->find($id)!=null){
      $data["issuance_data"] = $issuance->find($id);
      $data["issuance_data"]->date_ = date("m/d/Y",$data["issuance_data"]->date_);
      $data["issuance_data"]->date_time = date("h:i:s A",$data["issuance_data"]->date_time);
      $data["issuance_data"]->issuance_to = DB::table('issuance_to')->find($data["issuance_data"]->issuance_to)->name;
      $data["items"] = $issuance_detail->select('issuance_detail.*','inventory_item.item_name','inventory_item.category')->join('inventory_item','issuance_detail.inventory_item_id','=','inventory_item.id')->where("issuance_id",$id)->get();
    }else{
      abort(404);
    }
    return view('issuance.issuance',$data);
  }

  public function update_cart_items(Request $request,$id)
  {
    $data = $request->session()->get("issuance_cart");
    $data["items"]["item_".$id]["quantity"] = abs((float)round($request->quantity,2));
    $request->session()->put("issuance_cart",$data);
    return $this->show_cart($request);
  }

  public function add_info_cart(Request $request)
  {
    if($request->session()->has("issuance_cart")&&$request->session()->get("issuance_cart")!=array()){
      $data = $request->session()->get("issuance_cart");
    }else{
      $data["items"] = array();
    }
    $data["info"] = [
      'issuance_number' => $request->issuance_number,
      'issuance_to' => $request->issuance_to,
      'comments' => $request->comments
    ];
    $request->session()->put("issuance_cart",$data);
  }

  public function store_cart(Request $request,$id)
  {
    if($request->session()->has('issuance_cart')&&$request->session()->get('issuance_cart')!=array()){
      $data = $request->session()->get('issuance_cart');
      if($request->session()->has('issuance_cart.items.item_'.$id)){
        $data["items"]["item_".$id]["quantity"]++;
      }else{
        $data["items"]["item_".$id] = [
          'inventory_item_id' => $id,
          'quantity' => 1,
        ];
      }
    }else{
      $inventory_item = new Inventory_item;
      $data["items"]["item_".$id] = [
        'inventory_item_id' => $id,
        'quantity' => 1,
      ];
      $data["info"] = [
        'issuance_number' => '',
        'issuance_to' => '',
        'comments' => '',
      ];
    }
    $request->session()->put('issuance_cart',$data);
    return $this->show_cart($request);
  }
  public function show_cart(Request $request)
  {
    // return $request->session()->get('issuance_cart');
    $inventory_item = new Inventory_item;
    $data = array();
    if($request->session()->has("issuance_cart")&&$request->session()->get("issuance_cart")!=array()){
      $data = $request->session()->get("issuance_cart");
      foreach ($data["items"] as $cart_item) {
        $item_data = $inventory_item->find($cart_item["inventory_item_id"]);
        $data["items"]["item_".$cart_item["inventory_item_id"]]["category"] = $item_data->category;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["item_name"] = $item_data->item_name;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["unit"] = $item_data->unit;
        $stocks = $inventory_item
          ->join('inventory_item_detail','inventory_item.id','=','inventory_item_detail.inventory_item_id')
          ->select('inventory_item.*',DB::raw('SUM(quantity) as total'))
          ->where('inventory_item.deleted',0)
          ->where('inventory_item_detail.deleted',0)
          ->where('inventory_item.id',$item_data->id)
          ->value('total');
        $stocks = ($stocks==null?'0':$stocks);
        $data["items"]["item_".$cart_item["inventory_item_id"]]["stocks"] = $stocks;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["valid"] = ($stocks>=$cart_item["quantity"]?1:0);
      }
    }
    return $data;
  }

  public function store_issuance(Request $request)
  {
    // exit;
    $this->validate($request, [
        'items' => 'not_zero_quantity|valid_inventory_control:inventory_item_detail,inventory_item_id',
        'issuance_to' => 'required',
        'issuance_number' => 'required|max:255',
        'comments' => 'max:255'
    ],[
      'not_zero_quantity' => 'The quantity of items to be issued must not be less than 1.',
      'valid_inventory_control' => 'The number of quantity did not match to your remaining stocks.'
    ]);
    $inventory_item = new Inventory_item;
    $issuance = new Issuance;
    $issuance->issuance_number = $request->issuance_number;
    $issuance->issuance_to = $request->issuance_to;
    $issuance->comments = $request->comments;
    $issuance->date_ = strtotime(date("m/d/Y"));
    $issuance->date_time = strtotime(date("m/d/Y h:i:s A"));
    $issuance->save();
    $issuance_data = $issuance->orderBy("id","DESC")->first();

    foreach ($request->items as $cart_item) {
      $issuance_detail = new Issuance_detail;
      $issuance_detail->inventory_item_id = $cart_item["inventory_item_id"];
      $issuance_detail->quantity = $cart_item["quantity"];
      $issuance_detail->date_ = $issuance_data->date_;
      $issuance_detail->issuance_id = $issuance_data->id;
      $issuance_detail->user_id = $issuance_data->user_id;
      $issuance_detail->save();

      $item_data = $inventory_item->find($cart_item["inventory_item_id"]);

      $inventory_item_detail = new Inventory_item_detail;
      $inventory_item_detail->inventory_item_id = $cart_item["inventory_item_id"];
      $inventory_item_detail->quantity = 0-$cart_item["quantity"];
      $inventory_item_detail->cost_price = $item_data->cost_price;
      $inventory_item_detail->date_ = $issuance_data->date_;
      $inventory_item_detail->date_time = $issuance_data->date_time;
      $inventory_item_detail->remarks = "Issuance";
      $inventory_item_detail->reference_table = "issuance";
      $inventory_item_detail->reference_id = $issuance_data->id;
      $inventory_item_detail->user_id = $issuance_data->user_id;
      $inventory_item_detail->save();
/*
      $restaurant_inventory = new Restaurant_inventory;
      $restaurant_inventory->inventory_item_id = $cart_item["inventory_item_id"];
      $restaurant_inventory->quantity = $cart_item["quantity"];
      $restaurant_inventory->table = 'issuance';
      $restaurant_inventory->ref_id = $issuance_data->id;
      $restaurant_inventory->restaurant_id = DB::table('issuance_to')->find($request->issuance_to)->ref_id;
      $restaurant_inventory->save();*/
    }
    $request->session()->forget('issuance_cart');
    return $issuance_data->id;
  }

  public function delete_cart_items(Request $request,$id)
  {
    $request->session()->forget('issuance_cart.items.item_'.$id);
    return $this->show_cart($request);
  }
}
