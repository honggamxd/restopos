<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Issuance extends Model
{
  use SoftDeletes;
  protected $table = 'issuance';
  protected $dates = ['deleted_at'];
}
