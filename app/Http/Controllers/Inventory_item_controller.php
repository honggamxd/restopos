<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Inventory_item;
use App\Inventory_item_detail;


class Inventory_item_controller extends Controller
{
  public function index(Request $request)
  {
    # code...
  }
  public function store(Request $request)
  {
    $this->validate($request, [
        'category' => 'required|max:255',
        // 'subcategory' => 'required|max:255',
        'cost_price' => 'required|numeric',
        'item_name' => 'required|custom_unique:inventory_item,item_name,category,'.$request->category.'|max:255',
    ],[
      'custom_unique' => 'The :attribute has been already added.'
    ]);
    $inventory_item = new Inventory_item;
    $inventory_item->category = $request->category;
    // $inventory_item->subcategory = $request->subcategory;
    $inventory_item->item_name = $request->item_name;
    $inventory_item->cost_price = $request->cost_price;
    $inventory_item->save();

    $item_data = $inventory_item->orderBy("id","DESC")->first();

    $data["item_name"] = $request->item_name;
    app('App\Http\Controllers\Purchases_controller')->store_cart($request,$item_data->id);
    $data["cart"] = $request->session()->get("inventory"); 
    return $data;
    // $inventory_item->date_ = $request->cost_price;
  }

  public function search_item(Request $request,$type,$option)
  {
    if($type=="category"){
      $inventory_item = new Inventory_item;
      $search_data = $inventory_item->where('deleted',0);
      $search_data->where("category","like","%".$request->term."%");
      $search_data->limit(10);
      if($option=="value"){
        $data = array();
        foreach ($search_data->get() as $item_data) {
          $data[] = [
            "label" => $item_data->category,
            "value" => $item_data->category,
            "id" => $item_data->id,
          ];
        }
      }else{
        $data = $search_data->get()->pluck("category");
      }
    }else{
      $inventory_item = new Inventory_item;
      $search_data = $inventory_item->where('deleted',0);
      $search_data->where("item_name","like","%".$request->term."%");
      $search_data->limit(10);
      if($option=="value"){
        $data = array();
        foreach ($search_data->get() as $item_data) {
          $data[] = [
            "label" => $item_data->item_name,
            "value" => $item_data->item_name,
            "id" => $item_data->id,
          ];
        }
      }else{
        $data = $search_data->get()->pluck("item_name");
      }
    }
    return $data;
  }
}
