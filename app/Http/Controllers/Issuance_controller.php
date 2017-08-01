<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Issuance;
use App\Issuance_detail;
use App\Inventory_item;
use App\Inventory_item_detail;

class Issuance_controller extends Controller
{
  public function index(Request $request)
  {
    return view('issuance');
  }
  public function update_cart_items(Request $request,$id)
  {
    $data = $request->session()->get("issuance_cart");
    $data["items"]["item_".$id]["quantity"] = abs((integer)$request->quantity);
    $request->session()->put("issuance_cart",$data);
    // return $this->store_cart($request);
  }

  public function add_info_cart(Request $request)
  {
    if($request->session()->has("issuance_cart")&&$request->session()->get("issuance_cart")!=array()){
      $data = $request->session()->get("issuance_cart");
    }
    $data["info"] = [
      'issuance_number' => $request->issuance_number,
      'issuance_from' => $request->issuance_from,
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
        'issuance_from' => '',
        'comments' => '',
      ];
    }
    $request->session()->put('issuance_cart',$data);
  }
  public function show_cart(Request $request)
  {
    $inventory_item = new Inventory_item;
    $data = array();
    if($request->session()->has("issuance_cart")&&$request->session()->get("issuance_cart")!=array()){
      $data = $request->session()->get("issuance_cart");
      foreach ($data["items"] as $cart_item) {
        $item_data = $inventory_item->find($cart_item["inventory_item_id"]);
        $data["items"]["item_".$cart_item["inventory_item_id"]]["category"] = $item_data->category;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["item_name"] = $item_data->item_name;
        $stocks = $inventory_item
          ->join('inventory_item_detail','inventory_item.id','=','inventory_item_detail.inventory_item_id')
          ->select('inventory_item.*',DB::raw('SUM(quantity) as total'))
          ->where('inventory_item.deleted',0)
          ->where('inventory_item.id',$item_data->id)
          ->value('total');
        $data["items"]["item_".$cart_item["inventory_item_id"]]["stocks"] = ($stocks==null?'0':$stocks);
      }
    }
    return $data;
  }
}
