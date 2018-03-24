<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_capital_expenses_request_form_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_capital_expenses_request_form_detail';
  protected $dates = ['deleted_at'];
}
