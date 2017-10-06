<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant';
  protected $dates = ['deleted_at'];
}
