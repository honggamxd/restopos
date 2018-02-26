<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Issuance extends Model
{
  use SoftDeletes;
  protected $table = 'issuance';
  protected $dates = ['deleted_at'];
  protected $appends = ['total'];

  public function details()
  {
      return $this->hasMany('App\Issuance_detail');
  }

  public function getTotalAttribute()
  {
    $total = 0;
    foreach ($this->details as $detail) {
      $total += ($detail->quantity);
    }
    return $total;
  }
}
