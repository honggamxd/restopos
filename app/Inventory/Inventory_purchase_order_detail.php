<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_purchase_order_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_purchase_order_detail';
  protected $dates = ['deleted_at'];

  public function inventory_purchase_order()
  {
    return $this->belongsTo('App\Inventory\Inventory_purchase_order');
  }

  public function inventory_item()
  {
    return $this->belongsTo('App\Inventory\Inventory_item');
  }
}
