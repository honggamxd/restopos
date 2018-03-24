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
        $Inventory_receiving_report->uuid = $uuid->string;
        return $Inventory_receiving_report;
    });
  }

  public function details()
  {
      return $this->hasMany('App\Inventory\Inventory_receiving_report_detail');
  }
}
