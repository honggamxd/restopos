<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_stock_issuance extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_stock_issuance';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_stock_issuance) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $stock_issuance_number = Inventory_stock_issuance::withTrashed()->orderBy('stock_issuance_number','DESC')->first();
        $Inventory_stock_issuance->stock_issuance_number = $stock_issuance_number ? $stock_issuance_number->stock_issuance_number + 1 : 1;
        $Inventory_stock_issuance->uuid = $uuid->string;
        return $Inventory_stock_issuance;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_stock_issuance_detail');
  }

  public function inventory_receiving_report()
  {
      return $this->belongsTo('App\Inventory\Inventory_receiving_report');
  }
}
