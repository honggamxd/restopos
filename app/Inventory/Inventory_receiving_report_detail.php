<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_receiving_report_detail extends Model
{
  use SoftDeletes;  
  protected $table = 'inventory_receiving_report_detail';
  protected $dates = ['deleted_at'];

  public function inventory_recei()
  {
    return $this->belongsTo('App\Inventory\Inventory_receiving_reportinventory_recei');
  }

  public function inventory_item()
  {
    return $this->belongsTo('App\Inventory\Inventory_item');
  }
}
