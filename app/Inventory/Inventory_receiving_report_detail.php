<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_receiving_report_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_receiving_report_detail';
  protected $dates = ['deleted_at'];
}
