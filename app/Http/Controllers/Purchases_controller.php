<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Inventory_item;
use App\Inventory_item_detail;
use App\Purchase;
use App\Purchase_detail;

class Purchases_controller extends Controller
{
  public function index(Request $request)
  {
    return view('purchases');
  }
}
