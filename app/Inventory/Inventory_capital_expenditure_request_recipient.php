<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item_detail;
use DB;

class Inventory_capital_expenditure_request_recipient extends Model
{
    protected $table = 'inventory_capital_expenditure_request_recipient';
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($Inventory_capital_expenditure_request_recipient) {
            $uuid = \Webpatser\Uuid\Uuid::generate();
            $Inventory_capital_expenditure_request_recipient->uuid = $uuid->string;
            return $Inventory_capital_expenditure_request_recipient;
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
