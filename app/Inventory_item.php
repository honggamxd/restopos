<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory_item extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item';
  protected $dates = ['deleted_at'];
}
