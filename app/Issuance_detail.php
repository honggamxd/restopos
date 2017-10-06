<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Issuance_detail extends Model
{
  use SoftDeletes;
  protected $table = 'issuance_detail';
  protected $dates = ['deleted_at'];
}
