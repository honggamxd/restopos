<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
  
class Restaurant_meal_types extends Model
{
    //
  use SoftDeletes;
  protected $table = 'restaurant_meal_type';
  protected $dates = ['deleted_at'];
}
