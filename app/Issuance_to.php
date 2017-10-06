<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Issuance_to extends Model
{
  use SoftDeletes;  
  protected $table = 'issuance_to';
  protected $dates = ['deleted_at'];
}
