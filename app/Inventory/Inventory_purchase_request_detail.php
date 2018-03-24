<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_purchase_request_detail extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_purchase_request_detail';
  protected $dates = ['deleted_at'];
}
