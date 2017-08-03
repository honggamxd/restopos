<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_table;

class Restaurant_table_controller extends Controller
{
  public function get_list(Request $request,$type)
  {
    if($type=="serve"){
      $restaurant_table = new Restaurant_table;
      $data["result"] = $restaurant_table->where(["occupied"=>0,"deleted"=>0,'restaurant_id'=>$request->session()->get('users.user_data')->restaurant_id])->get();
      foreach ($data["result"] as $table_data) {
        $table_data->restaurant_name = DB::table('restaurant')->find($table_data->restaurant_id)->name;
      }
      return $data;
    }else{
      $restaurant_table = new Restaurant_table;
      $data["result"] = $restaurant_table->where(["deleted"=>0,'restaurant_id'=>$request->session()->get('users.user_data')->restaurant_id])->get();
      foreach ($data["result"] as $table_data) {
        $table_data->restaurant_name = DB::table('restaurant')->find($table_data->restaurant_id)->name;
      }
      return $data;
    }
  }

  public function store(Request $request)
  {
    $restaurant_table = new Restaurant_table;
    $restaurant_table->name = $request->name;
    $restaurant_table->restaurant_id = $request->session()->get('users.user_data')->restaurant_id;
    $restaurant_table->save();
    return $request->name;
  }
}
