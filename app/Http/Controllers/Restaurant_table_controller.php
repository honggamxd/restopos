<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_table;

class Restaurant_table_controller extends Controller
{
  public function __construct()
  {
      $this->middleware('logged');
  }
  public function get_list(Request $request,$type)
  {
    DB::enableQueryLog();
    
    if($type=="serve"){
      $restaurant_table = new Restaurant_table;
      $data["result"] = $restaurant_table->where("deleted",0);
      $data["result"]->where("restaurant_id",$request->session()->get('users.user_data')->restaurant_id);
      $data["result"]->orderBy("occupied","ASC");
      $data["result"]->orderBy("name","ASC");
      $data["result"] = $data["result"]->get();
      foreach ($data["result"] as $table_data) {
        $table_data->restaurant_name = DB::table('restaurant')->find($table_data->restaurant_id)->name;
        $table_data->table_name_status = $table_data->name.' - '.($table_data->occupied==0?"Available":"Occupied");
      }
      $data["getQueryLog"] = DB::getQueryLog();
  }else{
      $restaurant_table = new Restaurant_table;
      $data["result"] = $restaurant_table->where('deleted',0);
      $data["result"]->where('restaurant_id',$request->restaurant_id);
      $data["result"]->orderBy("name","ASC");
      $data["result"] = $data["result"]->get();
      foreach ($data["result"] as $table_data) {
        $table_data->restaurant_name = DB::table('restaurant')->find($table_data->restaurant_id)->name;
      }
    }
      return $data;
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|custom_unique:restaurant_table,name,restaurant_id,'.$request->restaurant_id.'|max:255',
    ],[
      'custom_unique' => 'This table name is already added in this outlet.'
    ]);
    $restaurant_table = new Restaurant_table;
    $restaurant_table->name = $request->name;
    $restaurant_table->restaurant_id = $request->restaurant_id;
    $restaurant_table->save();
    return $request->name;
  }
}
