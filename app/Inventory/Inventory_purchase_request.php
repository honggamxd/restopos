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
        $purchase_request_number = Inventory_purchase_request::withTrashed()->orderBy('purchase_request_number','DESC')->first();
        $Inventory_purchase_request->purchase_request_number = $purchase_request_number ? $purchase_request_number->purchase_request_number + 1 : 1;
        $Inventory_purchase_request->uuid = $uuid->string;
        return $Inventory_purchase_request;
    });
  }

  public function details()
    {
        return $this->hasMany('App\Inventory\Inventory_purchase_request_detail');
    }
}
