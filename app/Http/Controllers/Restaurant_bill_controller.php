<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Restaurant_bill_controller extends Controller
{
  public function index(Request $request,$id)
  {
    $data["print"] = $request->print;
    $data["id"] = $id;
    return view('restaurant.bill',$data);
  }
}
