<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item;

class Inventory_item_detail extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item_detail';
  protected $dates = ['deleted_at'];

  public function item()
  {
    return $this->belongsTo(Inventory_item::class, 'inventory_item_id');
  }
}
