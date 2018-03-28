<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_request_to_canvass_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_request_to_canvass_detail';
  protected $dates = ['deleted_at'];

  public function inventory_request_to_canvass()
  {
    return $this->belongsTo('App\Inventory\Inventory_request_to_canvass');
  }

  public function inventory_item()
  {
    return $this->belongsTo('App\Inventory\Inventory_item');
  }
}
