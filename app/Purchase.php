<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Purchase extends Model
{
  use SoftDeletes;
  protected $table = 'purchase';
  protected $dates = ['deleted_at'];
}
