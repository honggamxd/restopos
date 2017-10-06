<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_order_detail extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_order_detail';
  protected $dates = ['deleted_at'];
}
