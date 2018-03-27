<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item_detail;
use DB;

class Inventory_item extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_item';
  protected $dates = ['deleted_at'];

  protected $appends = ['average_cost','total_quantity'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_item) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $Inventory_item->uuid = $uuid->string;
        return $Inventory_item;
    });
  }

  public function details()
  {
    return $this->hasMany('App\Inventory\Inventory_item_detail');
  }

  public function getAverageCostAttribute()
  {
    $average_cost = Inventory_item_detail::select(DB::raw('AVG(unit_cost) as average_cost'))->where('inventory_item_id',$this->id)->value('average_cost');
    return $average_cost!=null ? $average_cost : 0; 
  }

  public function getTotalQuantityAttribute()
  {
    $average_cost = Inventory_item_detail::select(DB::raw('SUM(quantity) as total_quantity'))->where('inventory_item_id',$this->id)->value('total_quantity');
    return $average_cost!=null ? $average_cost : 0; 
  }

}
