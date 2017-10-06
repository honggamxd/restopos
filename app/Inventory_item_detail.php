<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_item_detail extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item_detail';
  protected $dates = ['deleted_at'];
}
