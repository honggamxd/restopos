<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_bill_detail extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_bill_detail';
  protected $dates = ['deleted_at'];
}
