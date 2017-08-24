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
    $app_config = DB::table('app_config')->first();
    $restaurant = DB::table('restaurant')->get();
    $data["categories"] = explode(',', $app_config->categories);
    $data["restaurants"] = $restaurant;
    $data["restaurant_name"] = DB::table('restaurant')->find($request->session()->get('users.user_data')->restaurant_id)->name;
    return view('restaurant.menu',$data);
  }

  public function store(Request $request)
  {
    $restaurant_menu = new Restaurant_menu;
    $restaurant_menu->name = $request->name;
    $restaurant_menu->category = $request->category;
    $restaurant_menu->subcategory = $request->subcategory;
    $restaurant_menu->price = $request->price;
    $restaurant_menu->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_menu->is_prepared = 1;
    $restaurant_menu->save();
  }

  public function get_list(Request $request,$type)
  {
    $restaurant_menu = new Restaurant_menu;
    if($type=="orders"){
      $data["result"] =  $restaurant_menu
        ->where('deleted',0)
        ->where('is_prepared',1)
        ->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)
        ->get();
    }else{
      $data["result"] =  $restaurant_menu
        ->where('category','!=','')
        ->where('subcategory','!=','')
        ->where('deleted',0)
        ->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id)
        ->get();
    }
    foreach ($data["result"] as $menu_data) {
      $menu_data->is_prepared = ($menu_data->is_prepared==1?TRUE:FALSE);
      $menu_data->name = ($menu_data->name==''?'Special Order':$menu_data->name);
    }
    return $data;
  }

  public function show_category(Request $request,$restaurant_id)
  {
    $restaurant_menu = new Restaurant_menu;
    $data["result"] = $restaurant_menu->select('category')->distinct()->pluck('category');
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
