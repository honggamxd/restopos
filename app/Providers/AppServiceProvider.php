<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('custom_min', function($attribute, $value, $parameters, $validator) {
            return $value>$parameters[0];
        });

        Validator::extend('custom_unique', function($attribute, $value, $parameters, $validator) {
            $db = DB::table($parameters[0])->where('deleted',0);
            $db->where($parameters[1],$value);
            $db->where($parameters[2],$parameters[3]);
            if(isset($parameters[4])){
                $db->where('id','<>',$parameters[4]);
            }
            return ($db->count()===0);
        });

        Validator::extend('not_zero_quantity', function($attribute, $value, $parameters, $validator) {
            foreach ($value as $data) {
                if($data["quantity"]<=0){
                    return false;
                }
            }
            return true;
        });

        Validator::extend('valid_inventory_control', function($attribute, $value, $parameters, $validator) {
            if(isset($parameters[0])){
                foreach ($value as $data) {
                    $stocks = DB::table($parameters[0])
                    ->select(DB::raw('SUM(quantity) as stocks'))
                    ->where($parameters[1],$data[$parameters[1]])
                    ->where('deleted',0)
                    ->value('stocks');
                    $stocks = ($stocks==null?'0':$stocks);
                    if($stocks<$data["quantity"]){
                        return false;
                        break;
                    }
                }
                return true;
            }else{
                foreach ($value as $data) {
                    if($data["valid"]==0){
                        return false;
                        break;
                    }
                }
                return true;
            }
        });

        Validator::extend('valid_restaurant_billing', function($attribute, $value, $parameters, $validator) {
            $valid = true;
            $count_zero_quantity = 0;
            foreach ($value as $bill_preview) {
                $item_data = DB::table('restaurant_temp_bill_detail')
                ->where('restaurant_menu_id',$bill_preview["id"])
                ->where('restaurant_temp_bill_id',$bill_preview["restaurant_temp_bill_id"])
                ->first();
                $quantity_to_bill = abs($bill_preview["quantity_to_bill"]);
                $quantity = $item_data->quantity;
                if($quantity_to_bill>$quantity){
                    $valid = false;
                }
                if($quantity_to_bill==0){
                    $count_zero_quantity++;
                }
            }
            if(count($value)==$count_zero_quantity){
                return false;
            }else{
                return $valid;
            }
        });

        Validator::extend('available_to_cook', function($attribute, $value, $parameters, $validator) {
            return false;
        });

        Validator::extend('unique_menu', function($attribute, $value, $parameters, $validator) {
            $restaurant_menu = DB::table('restaurant_menu')->where('deleted',0);
            $restaurant_menu->where('category',$parameters[0]);
            $restaurant_menu->where('subcategory',$parameters[1]);
            $restaurant_menu->where('name',$parameters[2]);
            $restaurant_menu->where('restaurant_id',$parameters[3]);
            if(isset($parameters[4])){
                $restaurant_menu->where('id','<>',$parameters[4]);
            }
            return $restaurant_menu->first()===null;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
