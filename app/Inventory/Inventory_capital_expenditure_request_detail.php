<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_capital_expenditure_request_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_capital_expenditure_request_detail';
  protected $dates = ['deleted_at'];

  public function inventory_capital_expenditure_request()
  {
    return $this->belongsTo('App\Inventory\Inventory_capital_expenditure_request');
  }

  public function inventory_item()
  {
    return $this->belongsTo('App\Inventory\Inventory_item');
  }
}
