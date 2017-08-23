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
    return view('purchases.index');
  }

  public function store_cart(Request $request,$id)
  {
    $inventory = new Inventory_item;
    $item_data = $inventory->find($id);
    if($request->session()->has("purchase_cart")&&$request->session()->get("purchase_cart")!=array()){
      $data = $request->session()->get("purchase_cart");
      if($request->session()->has("purchase_cart.items.item_".$id)){
        $data["items"]["item_".$id]["quantity"]++;
      }else{
        $data["items"]["item_".$id] = [
          'inventory_item_id' => $id,
          'quantity' => 1,
          'cost_price' => $item_data->cost_price
        ];
      }
      $request->session()->put("purchase_cart",$data);
    }else{
      $data["items"]["item_".$id] = [
        'inventory_item_id' => $id,
        'quantity' => 1,
        'cost_price' => $item_data->cost_price
      ];
      $data["info"] = [
        'po_number' => "",
        'comments' => ""
      ];
      $request->session()->put("purchase_cart",$data);
    }
      return $this->show_cart($request);
  }

  public function update_cart_items(Request $request,$id)
  {
    $data = $request->session()->get("purchase_cart");
    $data["items"]["item_".$id]["quantity"] = abs((float)round($request->quantity,2));
    $data["items"]["item_".$id]["cost_price"] = abs((float)round($request->cost_price,2));
    $request->session()->put("purchase_cart",$data);
    return $this->show_cart($request);
  }

  public function add_info_cart(Request $request)
  {
    if($request->session()->has("purchase_cart")&&$request->session()->get("purchase_cart")!=array()){
      $data = $request->session()->get("purchase_cart");
    }
    $data["info"] = [
      'po_number' => $request->po_number,
      'comments' => $request->comments
    ];
    $request->session()->put("purchase_cart",$data);
    return $this->show_cart($request);
  }

  public function delete_cart_items(Request $request,$id)
  {
    $request->session()->forget("purchase_cart.items.item_".$id);
    return $this->show_cart($request);
  }
  public function show_cart(Request $request)
  {
    $inventory = new Inventory_item;
    $data = array();
    if($request->session()->has("purchase_cart")&&$request->session()->get("purchase_cart")!=array()){
      $data = $request->session()->get("purchase_cart");
      $data["total"] = 0;
      foreach ($data["items"] as $cart_item) {
        $item_data = $inventory->find($cart_item["inventory_item_id"]);
        $data["items"]["item_".$cart_item["inventory_item_id"]]["category"] = $item_data->category;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["item_name"] = $item_data->item_name;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["unit"] = $item_data->unit;
        $data["items"]["item_".$cart_item["inventory_item_id"]]["total"] = $cart_item["quantity"] * $cart_item["cost_price"];
        $data["total"] += $cart_item["quantity"] * $cart_item["cost_price"];
      }
    }
    return $data;
  }

  public function show(Request $request,$id)
  {
    $purchase = new Purchase;
    $purchase_detail = new Purchase_detail;
    $data = array();
    if($purchase->find($id)!=null){
      $data["purchase_data"] = $purchase->find($id);
      $data["purchase_data"]->date_ = date("m/d/Y",$data["purchase_data"]->date_);
      $data["purchase_data"]->date_time = date("h:i:s A",$data["purchase_data"]->date_time);
      $data["items"] = $purchase_detail->select('purchase_detail.*','inventory_item.item_name','inventory_item.category')->join('inventory_item','purchase_detail.inventory_item_id','=','inventory_item.id')->where("purchase_id",$id)->get();
    }else{
      abort(404);
    }
    return view('purchases.purchases',$data);
  }

  public function destroy_cart(Request $request)
  {
    $request->session()->forget("purchase_cart");
    return $this->show_cart($request);
  }

  public function store_purchase(Request $request)
  {
    $this->validate($request, [
        'items' => 'not_zero_quantity',
        'po_number' => 'required|max:255',
        'comments' => 'max:255'
    ],[
      'not_zero_quantity' => 'The quantity of items to be purchased must not be less than 1.'
    ]);
    // $data = $request->session()->get("purchase_cart");
    $purchase = new Purchase;
    $purchase->po_number = $request->po_number;
    $purchase->comments = $request->comments;
    $purchase->date_ = strtotime(date("m/d/Y"));
    $purchase->date_time = strtotime(date("m/d/Y h:i:s A"));
    $purchase->save();
    $purchase_data = $purchase->orderBy("id","DESC")->first();
    
    foreach ($request->items as $cart_item) {
      $purchase_detail = new Purchase_detail;
      $purchase_detail->inventory_item_id = $cart_item["inventory_item_id"];
      $purchase_detail->quantity = $cart_item["quantity"];
      $purchase_detail->cost_price = $cart_item["cost_price"];
      $purchase_detail->date_ = $purchase_data->date_;
      $purchase_detail->purchase_id = $purchase_data->id;
      $purchase_detail->user_id = $purchase_data->user_id;
      $purchase_detail->save();

      $inventory_item_detail = new Inventory_item_detail;
      $inventory_item_detail->inventory_item_id = $cart_item["inventory_item_id"];
      $inventory_item_detail->quantity = $cart_item["quantity"];
      $inventory_item_detail->cost_price = $cart_item["cost_price"];
      $inventory_item_detail->date_ = $purchase_data->date_;
      $inventory_item_detail->date_time = $purchase_data->date_time;
      $inventory_item_detail->remarks = "Purchase";
      $inventory_item_detail->reference_table = "purchase";
      $inventory_item_detail->reference_id = $purchase_data->id;
      $inventory_item_detail->user_id = $purchase_data->user_id;
      $inventory_item_detail->save();

      $inventory_item = new Inventory_item;
      $item_data = $inventory_item->find($cart_item["inventory_item_id"]);
      $item_data->cost_price = $cart_item["cost_price"];
      $item_data->save();
    }
    $request->session()->forget("purchase_cart");
    return $purchase_data->id;
    // return $item_data;
  }

  public function cart(Request $request)
  {
    dd($request->session()->get("purchase_cart"));
  }



}
