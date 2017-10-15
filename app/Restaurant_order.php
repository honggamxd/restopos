<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_order extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_order';
  protected $dates = ['deleted_at'];

  public function detail()
  {
      return $this->hasMany('App\Restaurant_order_detail');
  }
}
