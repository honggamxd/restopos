<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_server extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_server';
  protected $dates = ['deleted_at'];
}

