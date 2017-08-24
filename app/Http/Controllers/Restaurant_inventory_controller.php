<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_inventory;
use App\Inventory_item;

class Restaurant_inventory_controller extends Controller
{
  public function index(Request $request)
  {
    return view('restaurant.inventory');
  }

  public function show_items(Request $request)
  {
    $inventory_item = new Inventory_item;
    $restaurant_inventory = new Restaurant_inventory;
    $data["result"] = $restaurant_inventory
      ->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)
      ->select('*',DB::raw('SUM(quantity) as total_quantity'))
      ->groupBy('inventory_item_id')
      ->join('inventory_item','inventory_item.id','=','restaurant_inventory.inventory_item_id')
      ->get();
    return $data;
  }
}
