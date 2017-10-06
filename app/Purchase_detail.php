<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Purchase_detail extends Model
{
  use SoftDeletes;
  protected $table = 'purchase_detail';
  protected $dates = ['deleted_at'];
}
