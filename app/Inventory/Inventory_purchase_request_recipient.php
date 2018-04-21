<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inventory\Inventory_item_detail;
use DB;

class Inventory_purchase_request_recipient extends Model
{
    use SoftDeletes;
    protected $table = 'inventory_purchase_request_recipient';
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
