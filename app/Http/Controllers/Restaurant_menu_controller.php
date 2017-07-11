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
    return view('restaurant-menu');
  }

  public function store(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    $restaurant_menu->name = $request->name;
    $restaurant_menu->category = $request->category;
    $restaurant_menu->subcategory = $request->subcategory;
    $restaurant_menu->price = $request->price;
    $restaurant_menu->save();
  }

  public function get_list(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    return $restaurant_menu->get();
  }
}
