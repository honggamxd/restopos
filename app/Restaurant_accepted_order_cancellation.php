<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
  
class Restaurant_accepted_order_cancellation extends Model
{
    //
  use SoftDeletes;
  protected $table = 'restaurant_accepted_order_cancellation';
  protected $dates = ['deleted_at'];
}
