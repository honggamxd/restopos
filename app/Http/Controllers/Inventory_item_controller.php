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
        'subcategory' => 'required|max:255',
        'cost_price' => 'required|numeric',
        'item_name' => 'required|unique:inventory_item,item_name,deleted,0|max:255',
    ]);
    $inventory_item = new Inventory_item;
    $inventory_item->category = $request->category;
    $inventory_item->subcategory = $request->subcategory;
    $inventory_item->item_name = $request->item_name;
    $inventory_item->cost_price = $request->cost_price;
    $inventory_item->save();

    $data["item_name"] = $request->item_name;
    $data["cart"]; 
    return $data;
    // $inventory_item->date_ = $request->cost_price;
  }
}
