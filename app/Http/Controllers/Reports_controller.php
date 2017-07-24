<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class Reports_controller extends Controller
{
  public function index(Request $request)
  {
    return view('reports');
  }

  public function restaurant(Request $request,$type)
  {
    if($type=="sales"){
      return view('restaurant.reports.sales');
    }
  }
}
