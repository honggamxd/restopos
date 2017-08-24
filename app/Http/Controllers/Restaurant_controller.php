<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Restaurant_menu;

class Restaurant_controller extends Controller
{
    public function index(Request $request)
    {
      if($request->session()->get('users.user_data')->privilege=="admin"){
        return view('home');
      }else{
        $data["restaurant_name"] = DB::table('restaurant')->find($request->session()->get('users.user_data')->restaurant_id)->name;
        return view('restaurant.home',$data);
      }
    }

    public function settings($value='')
    {
      $restaurant = DB::table('restaurant')->get();
      $data["restaurants"] = $restaurant;
      return view('restaurant.settings',$data);
    }

    public function login($value='')
    {

      return view('login');
      # code...
    }

    public function logout(Request $request)
    {
      $request->session()->flush();
      return redirect('/login');
    }

    public function add_server(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|custom_unique:restaurant_server,name,restaurant_id,'.$request->session()->get('users.user_data')->restaurant_id.'|max:255',
      ],[
        'custom_unique' => 'This server is already added in this outlet.'
      ]);
      DB::table('restaurant_server')->insert([
          [
            'name' => $request->name,
            'restaurant_id' => $request->session()->get('users.user_data')->restaurant_id
          ]
        ]);
      return $request->name;
    }

    public function show_server(Request $request)
    {
      $data["result"] = DB::table('restaurant_server')->where(['deleted'=>0,'restaurant_id'=>$request->session()->get('users.user_data')->restaurant_id])->get();
      return $data;
    }
}
