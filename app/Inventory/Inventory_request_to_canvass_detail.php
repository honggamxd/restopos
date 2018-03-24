<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_request_to_canvass_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_request_to_canvass_detail';
  protected $dates = ['deleted_at'];
}
