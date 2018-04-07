<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_capital_expenditure_request extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_capital_expenditure_request';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_capital_expenditure_request) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $Inventory_capital_expenditure_request->uuid = $uuid->string;
        return $Inventory_capital_expenditure_request;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_capital_expenditure_request_detail');
  }

  public function footer()
  {
      return $this->hasMany('App\Inventory\Inventory_capital_expenditure_request_footer');
  }

  public function inventory_purchase_request()
  {
      return $this->belongsTo('App\Inventory\Inventory_purchase_request');
  }
}
