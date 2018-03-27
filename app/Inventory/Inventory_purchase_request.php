<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_purchase_request extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_purchase_request';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_purchase_request) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $Inventory_purchase_request->uuid = $uuid->string;
        return $Inventory_purchase_request;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_purchase_request_detail');
  }
}
