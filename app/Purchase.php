<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Purchase extends Model
{
  use SoftDeletes;
  protected $table = 'purchase';
  protected $dates = ['deleted_at'];
  protected $appends = ['total'];

  public function details()
  {
      return $this->hasMany('App\Purchase_detail');
  }

  public function getTotalAttribute()
  {
    $total = 0;
    foreach ($this->details as $detail) {
      $total += ($detail->cost_price*$detail->quantity);
    }
    return $total;
  }
}
