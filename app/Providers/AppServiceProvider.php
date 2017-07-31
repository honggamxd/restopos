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
            if($value>$parameters[0]){
                return true;
            }else{
                return false;
            }
        });

        Validator::extend('custom_unique', function($attribute, $value, $parameters, $validator) {
            $db = DB::table($parameters[0])->where('deleted',0);
            $db->where($parameters[1],$value);
            $db->where($parameters[2],$parameters[3]);
            return ($db->count()===0);
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
