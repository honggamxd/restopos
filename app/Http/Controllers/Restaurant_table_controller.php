<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_table;

class Restaurant_table_controller extends Controller
{
  public function get_list(Request $request)
  {
    $restaurant_table = new Restaurant_table;
    $data["result"] = $restaurant_table->where(["occupied"=>0,"deleted"=>0])->get();
    return $data;
  }

  public function store(Request $request)
  {
    $restaurant_table = new Restaurant_table;
    $restaurant_table->name = $request->name;
    $restaurant_table->restaurant_id = 1;
    $restaurant_table->save();
    return $request->name;
  }
}
