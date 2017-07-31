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
Route::get('/restaurant/menu/category/{id}', 'Restaurant_menu_controller@show_category');
Route::put('/restaurant/menu/list/{id}', 'Restaurant_menu_controller@available_to_menu');

Route::post('/restaurant/table/add', 'Restaurant_table_controller@store');
Route::post('/restaurant/table/order/make/{id}', 'Restaurant_order_controller@store');
Route::get('/restaurant/table/order/view/{id}', 'Restaurant_order_controller@show');
Route::post('/restaurant/table/order/cart', 'Restaurant_table_customer_controller@order_cart');
Route::post('/restaurant/table/order/cart/update/{type}/{id}', 'Restaurant_table_customer_controller@update_cart_items');
Route::get('/restaurant/table/order/cart/{table_customer_id}', 'Restaurant_table_customer_controller@show');
Route::get('/restaurant/table/order/test', 'Restaurant_table_customer_controller@test');
Route::get('/restaurant/table/order/delete', 'Restaurant_table_customer_controller@delete');
Route::post('/restaurant/table/customer/add', 'Restaurant_table_customer_controller@store');
Route::post('/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@bill_out');
Route::get('/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@show_temp_bill');
Route::post('/restaurant/table/customer/bill/make/{id}', 'Restaurant_table_customer_controller@make_bill');
Route::get('/restaurant/table/customer/bill/view/{id}', 'Restaurant_table_customer_controller@show_bill');
Route::get('/restaurant/table/customer/bill/list/{id}', 'Restaurant_table_customer_controller@list_bill');
Route::get('/restaurant/table/customer/orders/{id}', 'Restaurant_table_customer_controller@show_order');
Route::get('/restaurant/table/customer/list', 'Restaurant_table_customer_controller@get_list');
Route::post('/restaurant/table/customer/remove/{id}', 'Restaurant_table_customer_controller@destroy');
Route::post('/restaurant/table/customer/payment/make/{id}', 'Restaurant_payment_controller@store');
Route::get('/restaurant/table/customer/payment/list/{id}', 'Restaurant_payment_controller@show');
Route::get('/restaurant/table/list/{type}', 'Restaurant_table_controller@get_list');

Route::get('/restaurant/order/{id}', 'Restaurant_order_controller@index');

Route::get('/reports', 'Reports_controller@index');
Route::get('/reports/restaurant/{type}', 'Reports_controller@restaurant');
Route::get('/print/reports/restaurant/{type}', 'Reports_controller@restaurant_print');



//api get
Route::get('/api/reports/restaurant/{type}', 'Reports_controller@restaurant_api');
Route::get('/api/search/inventory/item/{type}/{option}', 'Inventory_item_controller@search_item');


Route::get('/api/purchases/cart', 'Purchases_controller@show');
Route::get('/api/purchases/cart/show', 'Purchases_controller@cart');

//api post
Route::post('/api/inventory/item/add', 'Inventory_item_controller@store');
Route::post('/api/purchases/cart/item/add/{id}', 'Purchases_controller@store_cart');
Route::post('/api/purchases/cart/info', 'Purchases_controller@add_info_cart');
Route::put('/api/purchases/cart/item/update/{id}', 'Purchases_controller@update_cart_items');
Route::delete('/api/purchases/cart/item/delete/{id}', 'Purchases_controller@delete_cart_items');
Route::delete('/api/purchases/cart/delete', 'Purchases_controller@destroy_cart');

//pages
Route::get('/purchases', 'Purchases_controller@index');




Route::get('/restaurant', function () {
    return view('restaurant.home');
});

Route::get('/order', function () {
    return view('restaurant.order');
});

Route::get('restaurant/bill/{id}', function ($id) {
    return view('restaurant.bill',["id"=>$id]);
});

Route::get('/inventory', function () {
    return view('restaurant.inventory');
});

Route::get('/receiving', function () {
    return view('restaurant.receiving');
});

Route::get('/settings', function () {
    return view('restaurant.settings');
});


