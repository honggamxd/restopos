<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_menu;
use App\Restaurant;
use App\Issuance_to;

class Restaurant_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('logged');
    }
    public function index(Request $request)
    {
      if($request->session()->get('users.user_data')->privilege=="admin"){
        $app_config = DB::table('app_config')->first();
        $data["categories"] = explode(',', $app_config->categories);
        return view('inventory',$data);
      }elseif($request->session()->get('users.user_data')->privilege=="restaurant_admin"){
        $app_config = DB::table('app_config')->first();
        $restaurant = DB::table('restaurant')->get();
        $data["categories"] = explode(',', $app_config->categories);
        $data["restaurants"] = $restaurant;
        $data["restaurant_name"] = DB::table('restaurant')->find($request->session()->get('users.user_data')->restaurant_id)->name;
        return view('restaurant.menu',$data);
      }else{
        $data["restaurant_name"] = DB::table('restaurant')->find($request->session()->get('users.user_data')->restaurant_id)->name;
        $app_config = DB::table('app_config')->first();
        $data["categories"] = explode(',', $app_config->categories);
        return view('restaurant.home',$data);
      }
    }

    public function settings($value='')
    {
      $restaurant = DB::table('restaurant')->get();
      $data["restaurants"] = $restaurant;
      return view('restaurant.settings',$data);
    }

    public function add_server(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|custom_unique:restaurant_server,name,restaurant_id,'.$request->restaurant_id.'|max:255',
      ],[
        'custom_unique' => 'This server is already added in this outlet.'
      ]);
      DB::table('restaurant_server')->insert([
          [
            'name' => $request->name,
            'restaurant_id' => $request->restaurant_id
          ]
        ]);
      return $request->name;
    }

    public function show_server(Request $request)
    {
      $data["result"] = DB::table('restaurant_server')->where(['deleted'=>0,'restaurant_id'=>$request->restaurant_id])->get();
      return $data;
    }

    public function update(Request $request)
    {
      $this->validate($request, [
        'restaurant_name' => 'required|unique:restaurant,name,'.$request->restaurant_id.'|max:255',
      ]);

      $restaurant = new Restaurant;
      $restaurant_data = $restaurant->find($request->restaurant_id);
      $restaurant_data->name = $request->restaurant_name;
      $restaurant_data->save();

      $issuance_to = new Issuance_to;
      $issuance_data = $issuance_to->where('ref_table','restaurant')->where('ref_id',$request->restaurant_id)->first();
      $issuance_data->name = $request->restaurant_name;
      $issuance_data->save();
      return DB::table('restaurant')->get();
    }
}
