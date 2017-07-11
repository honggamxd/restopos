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
      return view('sample');
    }
}
