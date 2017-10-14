<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant_order_cancellation_detail extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_order_cancellation_detail';
  protected $dates = ['deleted_at'];
}
