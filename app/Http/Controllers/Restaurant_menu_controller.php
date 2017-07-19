<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_menu;

class Restaurant_menu_controller extends Controller
{

  public function index(Request $request)
  {
    return view('restaurant.menu');
  }

  public function store(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    $restaurant_menu->name = $request->name;
    $restaurant_menu->category = $request->category;
    $restaurant_menu->subcategory = $request->subcategory;
    $restaurant_menu->price = $request->price;
    $restaurant_menu->restaurant_id = $request->restaurant_id;
    $restaurant_menu->is_prepared = 1;
    $restaurant_menu->save();
  }

  public function get_list(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    $data["result"] = ($request->for=="orders"?$restaurant_menu->where(["is_prepared"=>1,"deleted"=>0])->get():$restaurant_menu->where(["deleted"=>0])->get());
    foreach ($data["result"] as $menu_data) {
      $menu_data->is_prepared = ($menu_data->is_prepared==1?TRUE:FALSE);
    }
    return $data;
  }

  public function available_to_menu(Request $request,$id)
  {
    $restaurant_menu = new Restaurant_menu;
    $menu = $restaurant_menu->find($id);
    $menu->is_prepared = ($request->is_prepared=="true"?1:0);
    $menu->save();
    return $menu->is_prepared;
  }
}
