<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Inventory_item;
use App\Inventory_item_detail;
use App\Purchase;
use App\Purchase_detail;

class Purchases_controller extends Controller
{
  public function index(Request $request)
  {
    return view('purchases');
  }

  public function store_cart(Request $request,$id)
  {
    $invetory_item = new Inventory_item;
    $item_data = $invetory_item->find($id);
    if($request->session()->has("inventory")&&$request->session()->get("inventory")!=array()){
      $data = $request->session()->get("inventory");
      if($request->session()->has("inventory.items.item_".$id)){
        $data["items"]["item_".$id]["quantity"]++;
      }else{
        $data["items"]["item_".$id] = [
          'inventory_item_id' => $id,
          'quantity' => 1,
          'cost_price' => $item_data->cost_price
        ];
      }
      $request->session()->put("inventory",$data);
    }else{
      $data["items"]["item_".$id] = [
        'inventory_item_id' => $id,
        'quantity' => 1,
        'cost_price' => $item_data->cost_price
      ];
      $request->session()->put("inventory",$data);
    }
  }

  public function update_cart_items(Request $request,$id)
  {
    $data = $request->session()->get("inventory");
    $data["items"]["item_".$id]["quantity"] = (float)$request->quantity;
    $data["items"]["item_".$id]["cost_price"] = (float)$request->cost_price;
    $request->session()->put("inventory",$data);
    return $this->show($request);
  }

  public function add_info_cart(Request $request)
  {
    if($request->session()->has("inventory")&&$request->session()->get("inventory")!=array()){
      $data = $request->session()->get("inventory");
    }
    $data["info"] = [
      'po_number' => $request->po_number,
      'comments' => $request->comments
    ];
    $request->session()->put("inventory",$data);
  }

  public function delete_cart_items(Request $request,$id)
  {
    $request->session()->forget("inventory.items.item_".$id);
  }
  public function show(Request $request)
  {
    $invetory_item = new Inventory_item;
    $data = array();
    if($request->session()->has("inventory")&&$request->session()->get("inventory")!=array()){
      $data = $request->session()->get("inventory");
      $data["total"] = 0;
      foreach ($data["items"] as $cart_item) {
        $item_data = $invetory_item->find($cart_item["inventory_item_id"]);
        $data["items"]["item_".$cart_item["inventory_item_id"]]["category"] = $item_data->category;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["item_name"] = $item_data->item_name;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["total"] = $cart_item["quantity"] * $cart_item["cost_price"];
        $data["total"] += $cart_item["quantity"] * $cart_item["cost_price"];
      }
    }
    return $data;
  }

  public function destroy_cart(Request $request)
  {
    $request->session()->forget("inventory");
    return $this->show($request);
  }

  public function cart(Request $request)
  {
    dd($request->session()->get("inventory"));
  }



}
