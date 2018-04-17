<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_request_to_canvass extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_request_to_canvass';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_request_to_canvass) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $request_to_canvass_number = Inventory_request_to_canvass::withTrashed()->orderBy('request_to_canvass_number','DESC')->first();
        $Inventory_request_to_canvass->request_to_canvass_number = $request_to_canvass_number ? $request_to_canvass_number->request_to_canvass_number + 1 : 1;
        $Inventory_request_to_canvass->uuid = $uuid->string;
        return $Inventory_request_to_canvass;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_request_to_canvass_detail');
  }
  
  public function inventory_purchase_request()
  {
      return $this->belongsTo('App\Inventory\Inventory_purchase_request');
  }
}
