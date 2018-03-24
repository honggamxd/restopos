<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_capital_expenses_request_form extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_capital_expenses_request_form';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_capital_expenses_request_form) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $Inventory_capital_expenses_request_form->uuid = $uuid->string;
        return $Inventory_capital_expenses_request_form;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_capital_expenses_request_form_detail');
  }
}
