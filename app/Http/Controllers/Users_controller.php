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
      $data['password'] = "Invalid Password";
      return view('login')->withErrors($data);
    }
  }

  public function login_index($value='')
  {
    return view('login');
    # code...
  }

  public function settings(Request $request)
  {
    $user_data = User::find($request->session()->get('users.user_data')->id);
    if($user_data->allow_edit_info==0){
      abort(403);
    }
    return view('account-settings',compact('user_data'));
  }
  public function save_settings(Request $request)
  {
    $this->validate($request, [
         'name' => 'required',
         'username' => 'required|min:5|max:12|unique:user,username,'.$request->id,
         'old_password' => 'required|password:user,'.$request->id,
         'password' => 'confirmed|min:5|max:12',
     ],[
        'old_password.required' => 'The password field is required.',
        'old_password.password' => 'Incorrect password.'
     ]);

    $user_data = User::find($request->id);
    if($request->password!=null){
      $user_data->password = md5($request->password);
    }
    $user_data->username = $request->username;
    $user_data->name = $request->name;
    $user_data->username = $request->username;
    $user_data->save();
    $data["user_data"] = $user_data;
    $data["user_data"]->restaurant = ($data["user_data"]->restaurant_id==0?"":DB::table('restaurant')->find($data["user_data"]->restaurant_id)->name);
    $request->session()->put('users',$data);
  }
  
  public function logout(Request $request)
  {
    $request->session()->flush();
    return redirect('/login');
  }


  public function show_users(Request $request)
  {
    $user = new User;
    $data['result'] = $user->all();
    foreach ($data['result'] as $user_data) {
      switch ($user_data->privilege) {
        case 'admin':
          # code...
          $user_data->str_privilege = "Admin";
          break;
        case 'restaurant_admin':
          # code...
          $user_data->str_privilege = "Restaurant Admin";
          break;
        case 'restaurant_cashier':
          # code...
          $user_data->str_privilege = "Restaurant Cashier";
          break;
        
        default:
          # code...
          break;
      }
      
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
        'username' => 'required|unique:user|min:5|max:12',
        'password' => 'required|min:5|max:12',
        'restaurant_id' => 'required_if:privilege,restaurant_cashier,restaurant_admin',
    ],
    [
      'restaurant_id.required_if' => 'The outlet field is required when privilege is restaurant admin or cashier.',
    ]
    );

    $user = new User;
    $user->name = $request->name;
    $user->username = $request->username;
    $user->password = md5($request->password);
    $user->privilege = $request->privilege;
    $user->allow_edit_info = ($request->allow_edit_info!=null?1:0);
    $user->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
    $user->save();

    return $this->show_users($request);
  }

  public function edit_privilege(Request $request,$id)
  {
    $this->validate($request, [
        'restaurant_id' => 'required_if:privilege,restaurant_cashier,restaurant_admin',
    ],
    [
      'restaurant_id.required_if' => 'The outlet field is required when privilege is restaurant admin or cashier.',
    ]
    );

    $user = new User;
    $user_data = $user->find($id);
    $user_data->privilege = $request->privilege;
    $user_data->password = md5($request->password);
    $user_data->allow_edit_info = ($request->allow_edit_info=='true'?1:0);
    $user_data->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
    $user_data->save();

    return $this->show_users($request);
  }
  public function delete(Request $request,$id)
  {
    $user = new User;
    $user_data = $user->find($id)->delete();
    return $this->show_users($request);
  }
}
