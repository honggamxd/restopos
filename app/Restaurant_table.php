<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_table extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_table';
  protected $dates = ['deleted_at'];
}
