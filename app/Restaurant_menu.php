<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_menu extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_menu';
  protected $dates = ['deleted_at'];
}
