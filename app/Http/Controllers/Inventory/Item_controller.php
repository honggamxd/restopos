<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;

class Item_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('inventory.items');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'item_name' => 'required|unique:inventory_item,item_name,NULL,id,deleted_at,NULL',
                'category' => 'required',
                'subcategory' => 'required',
                'unit_of_measure' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        return $this->list_bill($request,$id);    
    }

    public function update(Request $request)
    {
        # code...
    }

    public function delete(Request $request)
    {
        # code...
    }
}
