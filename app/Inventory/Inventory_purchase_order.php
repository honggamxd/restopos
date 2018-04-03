<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_purchase_order extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_purchase_order';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_purchase_order) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $Inventory_purchase_order->uuid = $uuid->string;
        return $Inventory_purchase_order;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_purchase_order_detail');
  }

  public function inventory_purchase_request()
  {
      return $this->belongsTo('App\Inventory\Inventory_purchase_request');
  }
  
}
