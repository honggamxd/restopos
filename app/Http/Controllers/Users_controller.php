<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\User;


class Users_controller extends Controller
{

  public function index(Request $request)
  {
    $restaurant = DB::table('restaurant')->get();
    $data["restaurants"] = $restaurant;
    return view('user',$data);
  }
  public function login(Request $request)
  {
    $this->validate($request, [
        'username' => 'required|exists:user,username|max:255',
        'password' => 'required',
    ]);
    $user = new User;
    $user_data = $user->where(['username'=>$request->username,'password'=>md5($request->password)]);
    if($user_data->count()!=0){
      $data["user_data"] = $user_data->first();
      $data["user_data"]->restaurant = ($data["user_data"]->restaurant_id==0?"":DB::table('restaurant')->find($data["user_data"]->restaurant_id)->name);
      $request->session()->put('users',$data);
      return redirect('/');
    }else{
      return view('login');
    }
  }

  public function show_users(Request $request)
  {
    $user = new User;
    $data['result'] = $user->all();
    foreach ($data['result'] as $user_data) {
      $user_data->privilege = ($user_data->privilege=="admin"?"Admin":"Restaurant Cashier");
      if($user_data->restaurant_id!=0){
        $user_data->restaurant_name = DB::table('restaurant')->find($user_data->restaurant_id)->name;
      }
    }
    return $data;
    # code...
  }

  public function add(Request $request)
  {
    // return $request;
    $this->validate($request, [
        'name' => 'required',
        'username' => 'required|unique:user|min:5',
        'password' => 'required|min:5',
        'restaurant_id' => 'required_if:privilege,restaurant_cashier',
    ],
    [
      'restaurant_id.required_if' => 'The outlet field is required when privilege is restaurant cashier.',
    ]
    );

    $user = new User;
    $user->name = $request->name;
    $user->username = $request->username;
    $user->password = md5($request->password);
    $user->privilege = $request->privilege;
    $user->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
    $user->save();

    return $request;
  }
}
