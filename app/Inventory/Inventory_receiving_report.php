<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory_receiving_report extends Model
{
  use SoftDeletes;
  protected $table = 'inventory_receiving_report';
  protected $dates = ['deleted_at'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function($Inventory_receiving_report) {
        $uuid = \Webpatser\Uuid\Uuid::generate();
        $receiving_report_number = Inventory_receiving_report::withTrashed()->orderBy('receiving_report_number','DESC')->first();
        $Inventory_receiving_report->receiving_report_number = $receiving_report_number ? $receiving_report_number->receiving_report_number + 1 : 1;
        $Inventory_receiving_report->uuid = $uuid->string;
        return $Inventory_receiving_report;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_receiving_report_detail');
  }

  public function inventory_purchase_order()
  {
      return $this->belongsTo('App\Inventory\Inventory_purchase_order');
  }
}
