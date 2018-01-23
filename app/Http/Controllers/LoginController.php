<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;

class LoginController extends Controller
{
  public function login(Request $request)
  {
      if (Auth::attempt(['username' => $request->username, 'password' => md5($request->password)])) {
          // Authentication passed...
          $user_data = User::find(Auth::user()->id);
          $data["user_data"] = $user_data;
          $data["user_data"]->restaurant = ($data["user_data"]->restaurant_id==0?"":DB::table('restaurant')->find($data["user_data"]->restaurant_id)->name);
          $request->session()->put('users',$data);
          return redirect()->intended('/');
      }
  }
}
