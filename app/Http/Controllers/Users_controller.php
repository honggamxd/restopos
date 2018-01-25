<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\User;
use App\Restaurant_bill_detail;
use App\Restaurant_bill;
use App\Restaurant_payment;
use App\Restaurant_meal_types;
use Carbon\Carbon;
use Auth;

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
    if(Auth::check()){
        return redirect('/');
    }else{
        return view('login');
    }
    # code...
  }

  public function settings(Request $request)
  {
    $user_data = User::find(Auth::user()->id);
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
    DB::beginTransaction();
    try{
        $user_data = User::find($request->id);
        if($request->password!=null){
          $user_data->password = bcrypt(md5($request->password));
        }
        $user_data->username = $request->username;
        $user_data->name = $request->name;
        $user_data->username = $request->username;
        $user_data->save();
        $data["user_data"] = $user_data;
        $data["user_data"]->restaurant = ($data["user_data"]->restaurant_id==0?"":DB::table('restaurant')->find($data["user_data"]->restaurant_id)->name);
        $request->session()->put('users',$data);
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}
  }
  
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->forget('users');
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
    DB::beginTransaction();
    try{
        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = bcrypt(md5($request->password));
        $user->privilege = $request->privilege;
        $user->allow_edit_info = ($request->allow_edit_info!=null?1:0);
        $user->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
        $user->save();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}

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
    DB::beginTransaction();
    try{
        
        $user = new User;
        $user_data = $user->find($id);
        $user_data->privilege = $request->privilege;
        $user_data->password = bcrypt(md5($request->password));
        $user_data->allow_edit_info = ($request->allow_edit_info=='true'?1:0);
        $user_data->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
        $user_data->save();
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}

    return $this->show_users($request);
  }
  public function delete(Request $request,$id)
  {
    $user = new User;
    $user_data = $user->find($id)->delete();
    return $this->show_users($request);
  }

  public function clean_bill_detail()
  {
    $test = DB::select('SELECT restaurant_menu_id,restaurant_bill_id FROM restaurant_bill_detail WHERE deleted_at IS NULL group by restaurant_menu_id,restaurant_bill_id having count(*) >= 2');
    $results= array();
    foreach ($test as $key => $value) {
      $result = Restaurant_bill_detail::where(['restaurant_menu_id'=>$value->restaurant_menu_id,'restaurant_bill_id'=>$value->restaurant_bill_id])->orderBy('id','DESC')->first();
      $result->url = url('restaurant/bill/'.$result->restaurant_bill_id);
      $bill_data = Restaurant_bill::find($result->restaurant_id);
      if($bill_data->type=="good_order"){
        $result->delete();
        $results[] = $result;
      }
    }
    $data['results'] = $results;
    return $data;
  }
  public function restaurant_payment()
  {
    $test = DB::select('SELECT restaurant_bill_id,settlement FROM restaurant_payment WHERE deleted_at IS NULL group by restaurant_bill_id,settlement having count(*) >= 2');
    $results= array();
    foreach ($test as $key => $value) {
      $result = Restaurant_payment::where(['restaurant_bill_id'=>$value->restaurant_bill_id,'settlement'=>$value->settlement])->orderBy('id','DESC')->first();
      $result->url = url('restaurant/bill/'.$result->restaurant_bill_id);
      $bill_data = Restaurant_bill::find($result->restaurant_id);
      if($bill_data->type=="good_order"){
        $result->delete();
        $results[] = $result;
      }
    }
    $data['results'] = $results;
    return $data;
  }
}
