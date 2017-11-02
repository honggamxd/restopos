<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Restaurant_bill extends Model
{
  use SoftDeletes;
  protected $table = 'restaurant_bill';
  protected $dates = ['deleted_at'];

  public function detail()
  {
      return $this->hasMany('App\Restaurant_bill_detail');
  }

  public function customer()
  {
      return $this->belongsTo('App\Restaurant_table_customer','restaurant_table_customer_id');
  }
}
