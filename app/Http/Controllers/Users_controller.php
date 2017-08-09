<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\User;


class Users_controller extends Controller
{
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
}
