<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_menu;
use App\Restaurant_menu_ingredients;

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
    // return $request->ingredients;
    $this->validate($request,[
        'name' => 'required',
        'category' => 'required',
        'subcategory' => 'required',
        'price' => 'required',
      ]);
    $restaurant_menu = new Restaurant_menu;
    $restaurant_menu->name = $request->name;
    $restaurant_menu->category = $request->category;
    $restaurant_menu->subcategory = $request->subcategory;
    $restaurant_menu->price = $request->price;
    $restaurant_menu->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_menu->is_prepared = 1;
    $restaurant_menu->save();

    $restaurant_menu_data = $restaurant_menu->orderBy('id','DESC')->first();
    return $restaurant_menu->orderBy('id','DESC')->first();
  }

  public function get_list(Request $request,$type)
  {
    $restaurant_menu = new Restaurant_menu;
    if($type=="orders"){
      $data["result"] =  $restaurant_menu->where('deleted',0);
      $data["result"]->where('is_prepared',1);
      $data["result"]->where('restaurant_id',$request->session()->get('users.user_data')->restaurant_id);
      if($request->category!=null&&$request->category!='all'){
        $data['result']->where('category',$request->category);
      }
      if($request->subcategory!=null&&$request->subcategory!='all'){
        $data['result']->where('subcategory',$request->subcategory);
      }
      $data["result"] = $data["result"]->get();
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

  public function search(Request $request,$type)
  {
    $data = array();
    $restaurant_menu = new Restaurant_menu;

    $search = $restaurant_menu->where('deleted',0);
    $search->where($type, 'like', '%'.$request->term.'%');
    $search->skip(0);
    $search->take(5);
    $search = $search->get();

    foreach ($search as $search_data) {
      $data[] = [
        'label' => $search_data->name,
        'value' => $search_data->name,
        'id' => $search_data->id
      ];
    }
    
    return $data;
  }


  public function show_subcategory(Request $request)
  {
    DB::enableQueryLog();
   
    $data = array();
    if($request->category!='all'){
      $restaurant_menu = new Restaurant_menu;
      $data = $restaurant_menu->where('category',$request->category)->select('subcategory')->distinct()->pluck('subcategory');
      // $
    }
    // $data["getQueryLog"] = DB::getQueryLog();
    return $data;
  }
}
