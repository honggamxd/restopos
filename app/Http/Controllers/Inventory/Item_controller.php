<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Auth;
use App\Inventory\Inventory_item;
use App\Inventory\Inventory_item_detail;

use App\Transformers\Inventory_item_transformer;

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

    public function get_list(Request $request)
    {
        $result = Inventory_item::query();
        if($request->term!=null&&trim($request->term)!=""){
            $result->where(function ($query) use ($request){
                $query->orWhere('name','LIKE',"%$request->term%")
                      ->orWhere('category','LIKE',"%$request->term%")
                      ->orWhere('subcategory','LIKE',"%$request->term%");
            });
        }
        $number_of_pages = $request->autocomplete ? 10 : 50;
        $pages = (string)$result->paginate($number_of_pages);
        $result = fractal($result->paginate($number_of_pages), new Inventory_item_transformer);
        $data['result'] = $result;
        $data['pages'] = $pages;
        return $data;
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'item_name' => 'required|unique:inventory_item,name,NULL,id,deleted_at,NULL',
                'category' => 'required',
                'subcategory' => 'required',
                'unit_of_measure' => 'required',
            ],
            [
                
            ]
        );
        DB::beginTransaction();
        try{
            $inventory_item = new Inventory_item;
            $inventory_item->name = $request->item_name;
            $inventory_item->category = $request->category;
            $inventory_item->subcategory = $request->subcategory;
            $inventory_item->unit_of_measure = $request->unit_of_measure;
            $inventory_item->save();
            DB::commit();
        }
        catch(\Exception $e){DB::rollback();throw $e;}
        // return $this->list_bill($request);    
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
