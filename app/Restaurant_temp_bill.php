<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_temp_bill extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_temp_bill';
  protected $dates = ['deleted_at'];
  
  public function detail()
  {
      return $this->hasMany('App\Restaurant_temp_bill_detail');
  }
}
