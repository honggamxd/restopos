<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_payment extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_payment';
  protected $dates = ['deleted_at'];
}
