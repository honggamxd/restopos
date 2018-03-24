<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory_item extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item';
  protected $dates = ['deleted_at'];

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_item_detail');
  }
}
