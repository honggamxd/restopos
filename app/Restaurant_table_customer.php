<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_table_customer extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_table_customer';
  protected $dates = ['deleted_at'];
}
