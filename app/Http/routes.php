<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Restaurant_controller@index');
Route::get('/restaurant/menu', 'Restaurant_menu_controller@index');
Route::post('/restaurant/menu', 'Restaurant_menu_controller@store');
Route::get('/restaurant/menu/list', 'Restaurant_menu_controller@get_list');
Route::put('/restaurant/menu/list/{id}', 'Restaurant_menu_controller@available_to_menu');

Route::post('/restaurant/table/order/make/{id}', 'Restaurant_order_controller@store');
Route::get('/restaurant/table/order/view/{id}', 'Restaurant_order_controller@show');
Route::post('/restaurant/table/order/cart', 'Restaurant_table_customer_controller@order_cart');
Route::get('/restaurant/table/order/cart/{table_customer_id}', 'Restaurant_table_customer_controller@show');
Route::get('/restaurant/table/order/test', 'Restaurant_table_customer_controller@test');
Route::get('/restaurant/table/order/delete', 'Restaurant_table_customer_controller@delete');
Route::post('/restaurant/table/customer/add', 'Restaurant_table_customer_controller@store');
Route::get('/restaurant/table/customer/list', 'Restaurant_table_customer_controller@get_list');
Route::get('/restaurant/table/list', 'Restaurant_table_controller@get_list');
Route::get('/restaurant/order/{id}', 'Restaurant_order_controller@index');

Route::get('/restaurant', function () {
    return view('restaurant.home');
});

Route::get('/order', function () {
    return view('restaurant.order');
});

Route::get('restaurant/bill', function () {
    return view('restaurant.bill');
});

Route::get('/inventory', function () {
    return view('restaurant.inventory');
});

Route::get('/receiving', function () {
    return view('restaurant.receiving');
});

Route::get('/reports', function () {
    return view('restaurant.reports');
});



