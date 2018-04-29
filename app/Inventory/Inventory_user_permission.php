<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class Inventory_user_permission extends Model
{
    //
    protected $table = 'inventory_user_permission';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
