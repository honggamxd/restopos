<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item_detail;
use DB;

class Inventory_stock_issuance_recipient extends Model
{
    protected $table = 'inventory_stock_issuance_recipient';
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($Inventory_stock_issuance_recipient) {
            $uuid = \Webpatser\Uuid\Uuid::generate();
            $Inventory_stock_issuance_recipient->uuid = $uuid->string;
            return $Inventory_stock_issuance_recipient;
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
