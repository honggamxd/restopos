<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item;
use App\Inventory\Inventory_receiving_report;
use App\Inventory\Inventory_stock_issuance;

class Inventory_item_detail extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item_detail';
  protected $dates = ['deleted_at'];

  public function item()
  {
    return $this->belongsTo(Inventory_item::class, 'inventory_item_id');
  }

  public function inventory_receiving_report()
  {
    return $this->belongsTo(Inventory_receiving_report::class, 'inventory_receiving_report_id');
  }

  public function inventory_stock_issuance()
  {
    return $this->belongsTo(Inventory_stock_issuance::class, 'inventory_stock_issuance_id');
  }
}
