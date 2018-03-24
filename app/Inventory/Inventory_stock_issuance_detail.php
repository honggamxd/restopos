<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_stock_issuance_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_stock_issuance_detail';
  protected $dates = ['deleted_at'];
}
