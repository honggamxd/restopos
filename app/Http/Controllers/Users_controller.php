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
use App\Inventory\Inventory_user_permission;
use Carbon\Carbon;
use Auth;
use App;
use PDF;

use App\Transformers\User_transformer;

class Users_controller extends Controller
{
  public function __construct()
  {
    // $this->middleware('auth');
    $this->check_json_settings();
  }
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

  private function check_json_settings()
  {
    if(!file_exists(public_path('settings/user.json'))){
        $data = array();
        $data['positions'] = [
          'Resort Manager',
          'Managing Director',
          'General Manager',
          'HR Manager',
          'Cash & Bank',
          'Purchasing',
          'Warehouse Representative',
          'Department Head',
          'Finance Personel'
        ];
        $fp = fopen('settings/user.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }
  }

  public function update_settings(Request $request)
  {
      $data = array();
      $data['positions'] = $request->positions;
      $fp = fopen('settings/user.json', 'w');
      fwrite($fp, json_encode($data));
      fclose($fp);
  }

  public function get_settings(Request $request)
  {
      $string = file_get_contents(public_path("settings/user.json"));
      return $string;
  }


  public function show_users(Request $request)
  {
    $users = User::query();
    $users->orderBy('name');
    if($request->searchString){
      $searchString = $request->searchString;
      $users->where('name','LIKE',"%$searchString%");
    }
    if($request->fieldName){
      $users->where($request->fieldName,$request->fieldValue);
    }
    if($request->term){
      $users->select('name');
      $users->distinct();
    }
    $users = $users->get();
    $data['result'] = fractal($users, new User_transformer)->parseIncludes('restaurant,permissions')->toArray();
    if($request->term){
      $autocomplete = array();
      foreach ($data['result']['data'] as $user) {
        $autocomplete[] = [
          'label' => $user['name'],
          'id' => $user['id'],
          'value' => $user['name'],
        ];
      }
      return $autocomplete;
    }else{
      return $data;
    }
  }

  public function add(Request $request)
  {
    // return $request;
    $this->validate($request, [
        'name' => 'required',
        'username' => 'required|unique:user,username,NULL,id,deleted_at,NULL|min:5|max:12',
        'password' => 'required|min:5|max:12',
        'email_address' => 'email|unique:user,email_address,NULL,id,deleted_at,NULL',
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
        $user->is_valid = 1;
        $user->position = $request->privilege=='inventory_user' || $request->privilege=='admin' ? $request->position : null;
        $user->email_address = $request->email_address;
        $user->allow_edit_info = ($request->allow_edit_info!=null?1:0);
        $user->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
        $user->save();

        if($request->privilege=='inventory_user'){
          $user = User::orderBy('id','DESC')->first();
          $permission = new Inventory_user_permission;
          $permission->user_id = $user->id;
          $permission->can_view_items = $request->permissions['can_view_items'] == 'true' ? 1 : 0;
          $permission->can_add_items = $request->permissions['can_add_items'] == 'true' ? 1 : 0;
          $permission->can_edit_items = $request->permissions['can_edit_items'] == 'true' ? 1 : 0;
          $permission->can_delete_items = $request->permissions['can_delete_items'] == 'true' ? 1 : 0;
          $permission->can_view_purchase_requests = $request->permissions['can_view_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_add_purchase_requests = $request->permissions['can_add_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_edit_purchase_requests = $request->permissions['can_edit_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_delete_purchase_requests = $request->permissions['can_delete_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_approve_purchase_requests = $request->permissions['can_approve_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_view_request_to_canvasses = $request->permissions['can_view_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_add_request_to_canvasses = $request->permissions['can_add_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_edit_request_to_canvasses = $request->permissions['can_edit_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_delete_request_to_canvasses = $request->permissions['can_delete_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_view_capital_expenditure_requests = $request->permissions['can_view_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_add_capital_expenditure_requests = $request->permissions['can_add_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_edit_capital_expenditure_requests = $request->permissions['can_edit_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_delete_capital_expenditure_requests = $request->permissions['can_delete_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_approve_capital_expenditure_requests = $request->permissions['can_approve_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_view_purchase_orders = $request->permissions['can_view_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_add_purchase_orders = $request->permissions['can_add_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_edit_purchase_orders = $request->permissions['can_edit_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_delete_purchase_orders = $request->permissions['can_delete_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_approve_purchase_orders = $request->permissions['can_approve_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_view_receiving_reports = $request->permissions['can_view_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_add_receiving_reports = $request->permissions['can_add_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_edit_receiving_reports = $request->permissions['can_edit_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_delete_receiving_reports = $request->permissions['can_delete_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_view_stock_issuances = $request->permissions['can_view_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_add_stock_issuances = $request->permissions['can_add_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_edit_stock_issuances = $request->permissions['can_edit_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_delete_stock_issuances = $request->permissions['can_delete_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_approve_stock_issuances = $request->permissions['can_approve_stock_issuances'] == 'true' ? 1 : 0;
          $permission->save();
        }
        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}

    return $this->show_users($request);
  }

  public function edit_privilege(Request $request,$id)
  {
    if($request->email_address){
      $validations = [
          'restaurant_id' => 'required_if:privilege,restaurant_cashier,restaurant_admin',
          'email_address' => 'email|unique:user,email_address,'.$id.',id,deleted_at,NULL',
      ];
    }else{
        $validations = [
            'restaurant_id' => 'required_if:privilege,restaurant_cashier,restaurant_admin',
        ];
    }
    $this->validate($request, $validations,
    [
      'restaurant_id.required_if' => 'The outlet field is required when privilege is restaurant admin or cashier.',
    ]
    );
    // return $request->email_address;
    DB::beginTransaction();
    try{
        
        $user = new User;
        $user_data = $user->find($id);
        $user_data->privilege = $request->privilege;
        $user_data->is_valid = $request->is_valid ? 1 : 0;
        $user_data->email_address = $request->email_address;
        $user_data->position = $request->privilege=='inventory_user' || $request->privilege=='admin' ? $request->position : null;
        if($request->password){
          $user_data->password = bcrypt(md5($request->password));
        }
        $user_data->allow_edit_info = ($request->allow_edit_info=='true'?1:0);
        $user_data->restaurant_id = ($request->restaurant_id==null||$request->privilege=='admin'?0:$request->restaurant_id);
        $user_data->save();

        if($request->privilege=='inventory_user'){
          $permission = Inventory_user_permission::where('user_id',$id)->first();
          $permission->can_view_items = $request->permissions['can_view_items'] == 'true' ? 1 : 0;
          $permission->can_add_items = $request->permissions['can_add_items'] == 'true' ? 1 : 0;
          $permission->can_edit_items = $request->permissions['can_edit_items'] == 'true' ? 1 : 0;
          $permission->can_delete_items = $request->permissions['can_delete_items'] == 'true' ? 1 : 0;
          $permission->can_view_purchase_requests = $request->permissions['can_view_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_add_purchase_requests = $request->permissions['can_add_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_edit_purchase_requests = $request->permissions['can_edit_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_delete_purchase_requests = $request->permissions['can_delete_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_approve_purchase_requests = $request->permissions['can_approve_purchase_requests'] == 'true' ? 1 : 0;
          $permission->can_view_request_to_canvasses = $request->permissions['can_view_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_add_request_to_canvasses = $request->permissions['can_add_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_edit_request_to_canvasses = $request->permissions['can_edit_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_delete_request_to_canvasses = $request->permissions['can_delete_request_to_canvasses'] == 'true' ? 1 : 0;
          $permission->can_view_capital_expenditure_requests = $request->permissions['can_view_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_add_capital_expenditure_requests = $request->permissions['can_add_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_edit_capital_expenditure_requests = $request->permissions['can_edit_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_delete_capital_expenditure_requests = $request->permissions['can_delete_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_approve_capital_expenditure_requests = $request->permissions['can_approve_capital_expenditure_requests'] == 'true' ? 1 : 0;
          $permission->can_view_purchase_orders = $request->permissions['can_view_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_add_purchase_orders = $request->permissions['can_add_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_edit_purchase_orders = $request->permissions['can_edit_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_delete_purchase_orders = $request->permissions['can_delete_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_approve_purchase_orders = $request->permissions['can_approve_purchase_orders'] == 'true' ? 1 : 0;
          $permission->can_view_receiving_reports = $request->permissions['can_view_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_add_receiving_reports = $request->permissions['can_add_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_edit_receiving_reports = $request->permissions['can_edit_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_delete_receiving_reports = $request->permissions['can_delete_receiving_reports'] == 'true' ? 1 : 0;
          $permission->can_view_stock_issuances = $request->permissions['can_view_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_add_stock_issuances = $request->permissions['can_add_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_edit_stock_issuances = $request->permissions['can_edit_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_delete_stock_issuances = $request->permissions['can_delete_stock_issuances'] == 'true' ? 1 : 0;
          $permission->can_approve_stock_issuances = $request->permissions['can_approve_stock_issuances'] == 'true' ? 1 : 0;
          $permission->save();
        }

        DB::commit();
    }
    catch(\Exception $e){DB::rollback();throw $e;}

    return $this->show_users($request);
  }
  public function delete(Request $request,$id)
  {
    $user = new User;
    $user_data = $user->find($id)->delete();
    Inventory_user_permission::where('user_id',$id)->delete();
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

  public function purchase_request(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.purchase-request', $data);
    // return view('pdf.purchase-request');
    return $pdf->stream('invoice.pdf');
  }

  public function purchase_order(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.purchase-order', $data);
    // return view('pdf.purchase-order');
    return $pdf->stream('invoice.pdf');
  }

  public function receiving_report(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.receiving-report', $data);
    // return view('pdf.receiving-report');
    return $pdf->stream('invoice.pdf');
  }
  public function stock_issuance(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.stock-issuance', $data);
    // return view('pdf.stock-issuance');
    return $pdf->stream('invoice.pdf');
  }
  public function request_to_canvass(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.request-to-canvass', $data);
    // return view('pdf.request-to-canvass');
    return $pdf->stream('invoice.pdf');
  }
  public function capital_expenditure_request(Request $request)
  {
    $data['test'] = 'adhadhjasd';
    $pdf = PDF::setOptions(['dpi' => 600, 'defaultFont' => 'Helvetica']);
    $pdf->setPaper('legal', 'portrait');
    $pdf->loadView('pdf.capital-expenditure-request', $data);
    // return view('pdf.capital-expenditure-request');
    return $pdf->stream('invoice.pdf');
  }
}
