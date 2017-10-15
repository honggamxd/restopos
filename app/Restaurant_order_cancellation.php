<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_order_cancellation extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_order_cancellation';
  protected $dates = ['deleted_at'];

  public function detail()
  {
      return $this->hasMany('App\restaurant_order_cancellation_detail');
  }
}
