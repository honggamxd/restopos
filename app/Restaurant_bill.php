<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_bill extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_bill';
  protected $dates = ['deleted_at'];
}
