<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_menu;
use App\Restaurant_menu_ingredients;
use Auth;

class Restaurant_menu_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index(Request $request)
  {
    $app_config = DB::table('app_config')->first();
    $restaurant = DB::table('restaurant')->get();
    $data["categories"] = explode(',', $app_config->categories);
    $data["restaurants"] = $restaurant;
    $data["restaurant_name"] = DB::table('restaurant')->find(Auth::user()->restaurant_id)->name;
    return view('restaurant.menu',$data);
  }

  public function store(Request $request)
  {
    // return $request->ingredients;
    $this->validate($request,[
        'name' => 'required|unique_menu:'.$request->category.','.$request->subcategory.','.$request->name.','.Auth::user()->restaurant_id,
        'category' => 'required',
        'subcategory' => 'required',
        'price' => 'required',
    ],[
      'unique_menu' => 'Menu is already in the list.'
    ]);
    DB::beginTransaction();
    try{
        $restaurant_menu = new Restaurant_menu;
        $restaurant_menu->name = strtoupper($request->name);
        $restaurant_menu->category = strtoupper($request->category);
        $restaurant_menu->subcategory = strtoupper($request->subcategory);
        $restaurant_menu->price = $request->price;
        $restaurant_menu->restaurant_id = Auth::user()->restaurant_id;
        $restaurant_menu->is_prepared = 1;
        $restaurant_menu->save();

        $restaurant_menu_data = $restaurant_menu->orderBy('id','DESC')->first();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $restaurant_menu->orderBy('id','DESC')->first();
  }

  public function update(Request $request)
  {
    // return $request->all();
    $this->validate($request,[
        'name' => 'required|unique_menu:'.$request->category.','.$request->subcategory.','.$request->name.','.Auth::user()->restaurant_id.','.$request->id,
        'category' => 'required',
        'subcategory' => 'required',
        'price' => 'required',
    ],[
      'unique_menu' => 'Menu is already in the list.'
    ]);
    DB::beginTransaction();
    try{
        $restaurant_menu = new Restaurant_menu;
        $restaurant_menu_data = $restaurant_menu->find($request->id);
        $restaurant_menu_data->name = strtoupper($request->name);
        $restaurant_menu_data->category = strtoupper($request->category);
        $restaurant_menu_data->subcategory = strtoupper($request->subcategory);
        $restaurant_menu_data->price = $request->price;
        $restaurant_menu_data->save();

        $restaurant_menu_data = $restaurant_menu->orderBy('id','DESC')->first();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $restaurant_menu->orderBy('id','DESC')->first();
  }

  public function get_list(Request $request,$type)
  {
    $restaurant_menu = new Restaurant_menu;
    if($type=="orders"){
      $data["result"] =  $restaurant_menu->where('deleted',0);
      $data["result"]->where('is_prepared',1);
      $data["result"]->where('name','like','%'.$request->search.'%');
      $data["result"]->orderBy('name');
      $data["result"]->where('restaurant_id',Auth::user()->restaurant_id);
      if($request->category!=null&&$request->category!='all'){
        $data['result']->where('category',$request->category);
      }
      if($request->subcategory!=null&&$request->subcategory!='all'){
        $data['result']->where('subcategory',$request->subcategory);
      }
      $data["result"] = $data["result"]->get();
    }else{
      DB::enableQueryLog();
      
      $data["result"] =  $restaurant_menu->query();
      $data["result"]->where('category','!=','');
      $data["result"]->where('subcategory','!=','');
      $data["result"]->where('name','like','%'.$request->search.'%');
      $data["result"]->where('deleted',0);
      $data["result"]->orderBy('name');
      $data["result"]->where('restaurant_id',Auth::user()->restaurant_id);
      if($request->category!=null&&$request->category!='all'){
        $data['result']->where('category',$request->category);
      }
      if($request->subcategory!=null&&$request->subcategory!='all'){
        $data['result']->where('subcategory',$request->subcategory);
      }
      $data["pagination"] = (string)$data["result"]->paginate(50);
      $data["result"] = $data["result"]->paginate(50);
      $data["getQueryLog"] = DB::getQueryLog();
    }

    foreach ($data["result"] as $menu_data) {
      $menu_data->is_prepared = ($menu_data->is_prepared==1?TRUE:FALSE);
      $menu_data->name = ($menu_data->name==''?'Special Order':$menu_data->name);
    }
    return $data;
  }



  public function available_to_menu(Request $request,$id)
  {
    DB::beginTransaction();
    try{
        $restaurant_menu = new Restaurant_menu;
        $menu = $restaurant_menu->find($id);
        $menu->is_prepared = ($request->is_prepared=="true"?1:0);
        $menu->save();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
    return $menu->is_prepared;
  }

  public function search(Request $request,$type)
  {
    $data = array();
    $restaurant_menu = new Restaurant_menu;

    $search = $restaurant_menu->query();
    $search->where($type, 'like', '%'.$request->term.'%');
    $search->orderBy('name');
    if($request->type=="order"){
      $search->where('is_prepared',1);
    }
    $search->where('restaurant_id',Auth::user()->restaurant_id);
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
  public function list_subcategory(Request $request)
  {
    $data = array();
    $restaurant_menu = new Restaurant_menu;

    $search = $restaurant_menu->query();
    $search->where('subcategory', 'like', '%'.$request->term.'%');
    if($request->category!=null){
      $search->where('category', 'like', $request->category);
    }
    $search->orderBy('subcategory');
    $search->where('restaurant_id',Auth::user()->restaurant_id);
    $search->skip(0);
    $search->take(20);
    $search->distinct();
    $search->select('subcategory');
    $search = $search->get();

    foreach ($search as $search_data) {
      $data[] = [
        'label' => $search_data->subcategory,
        'value' => $search_data->subcategory,
        'id' => $search_data->id
      ];
    }
    
    return $data;
  }

  public function show_category(Request $request,$restaurant_id)
  {
    $restaurant_menu = new Restaurant_menu;
    $data["result"] = $restaurant_menu->query();
    $data["result"]->orderBy('category');
    $data["result"]->select('category');
    if(Auth::user()->restaurant_id!=0){
      $data["result"]->where('restaurant_id',Auth::user()->restaurant_id);
    }
    if($request->restaurant_id!=0){
      $data["result"]->where('restaurant_id',$request->restaurant_id);
    }
    $data["result"]->distinct();
    $data["result"] = $data["result"]->pluck('category');
    return $data;
  }
  public function show_subcategory(Request $request)
  {
   
    $data = array();
    if($request->category!='all'){
      $restaurant_menu = new Restaurant_menu;
      $data = $restaurant_menu->query();
      $data->orderBy('subcategory');
      $data->where('category',$request->category)->select('subcategory');
      if(Auth::user()->restaurant_id!=0){
        $data->where('restaurant_id',Auth::user()->restaurant_id);
      }
      if($request->restaurant_id!=0){
        $data->where('restaurant_id',$request->restaurant_id);
      }
      $data->distinct();
      $data = $data->pluck('subcategory');
    }
    return $data;
  }

  public function destroy($id)
  {
    Restaurant_menu::find($id)->delete();
  }
}
