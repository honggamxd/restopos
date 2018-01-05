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
            return $value>=$parameters[0];
        });

        Validator::extend('custom_max', function($attribute, $value, $parameters, $validator) {
            return $value<=$parameters[0];
        });

        Validator::extend('password', function($attribute, $value, $parameters, $validator) {
            $user_data = DB::table($parameters[0])->where('id',$parameters[1])->first();
            return $user_data->password===md5($value);
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

        Validator::extend('discount_except', function($attribute, $value, $parameters, $validator) {
            if($parameters[1]==0){
                return true;
            }
            foreach ($value as $bill_preview) {
                if($bill_preview['quantity_to_bill']==0){
                    continue;
                }
                if($bill_preview['category']==$parameters[0]){
                    return false;
                    break;
                }
            }
            return true;
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

        Validator::extend('cancellation_request', function($attribute, $value, $parameters, $validator) {
            DB::enableQueryLog();
            $cancellation_request = DB::table('restaurant_order_cancellation')
            ->where('id',$value)
            ->where('approved',0)
            ->whereNull('deleted_at')
            ->first();
            return $cancellation_request!==NULL;
        });

        Validator::extend('cancellation_request_items', function($attribute, $value, $parameters, $validator) {
            if($parameters[0]=="before_bill_out"){
                $valid = true;
                $count_zero_quantity = 0;
                foreach ($value as $item_data) {
                    $quantity_of_orders = DB::table('restaurant_order_detail')->where('id',$item_data['id'])->value('quantity');
                    $quantity_to_cancel = (isset($item_data['quantity_to_cancel'])?$item_data['quantity_to_cancel']:0);
                    if($quantity_to_cancel>$quantity_of_orders){
                        $valid = false;
                        break;
                    }
                    if($quantity_to_cancel==0){
                        $count_zero_quantity++;
                    }
                }
                if(count($value)==$count_zero_quantity){
                    return false;
                }else{
                    return $valid;
                }
                return true;
            }elseif($parameters[0]=="after_bill_out"){
                $valid = true;
                $count_zero_quantity = 0;
                foreach ($value as $bill_preview) {
                    $item_data = DB::table('restaurant_temp_bill_detail')
                    ->where('restaurant_menu_id',$bill_preview["id"])
                    ->where('restaurant_temp_bill_id',$bill_preview["restaurant_temp_bill_id"])
                    ->first();
                    $quantity_to_cancel = abs($bill_preview["quantity_to_cancel"]);
                    $quantity = $item_data->quantity;
                    if($quantity_to_cancel>$quantity){
                        $valid = false;
                    }
                    if($quantity_to_cancel==0){
                        $count_zero_quantity++;
                    }
                }
                if(count($value)==$count_zero_quantity){
                    return false;
                }else{
                    return $valid;
                }
                return true;
            }
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
