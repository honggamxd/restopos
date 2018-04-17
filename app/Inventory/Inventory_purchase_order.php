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
        $purchase_order_number = Inventory_purchase_order::withTrashed()->orderBy('purchase_order_number','DESC')->first();
        $Inventory_purchase_order->purchase_order_number = $purchase_order_number ? $purchase_order_number->purchase_order_number + 1 : 1;
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
