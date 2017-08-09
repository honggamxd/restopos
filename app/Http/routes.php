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

Route::get('/', 'Restaurant_controller@index')->middleware('logged');
Route::get('/restaurant/menu', 'Restaurant_menu_controller@index')->middleware('logged');
Route::post('/api/restaurant/menu', 'Restaurant_menu_controller@store');

Route::get('/api/restaurant/menu/list', 'Restaurant_menu_controller@get_list');
Route::get('/api/restaurant/menu/category/{id}', 'Restaurant_menu_controller@show_category');
Route::put('/api/restaurant/menu/list/{id}', 'Restaurant_menu_controller@available_to_menu');

Route::post('/api/restaurant/table/add', 'Restaurant_table_controller@store');
Route::post('/api/restaurant/table/order/make/{id}', 'Restaurant_order_controller@store');

Route::get('/api/restaurant/table/order/view/{id}', 'Restaurant_order_controller@show');
Route::post('/api/restaurant/table/order/cart', 'Restaurant_table_customer_controller@order_cart');
Route::post('/api/restaurant/table/order/cart/update/{type}/{id}', 'Restaurant_table_customer_controller@update_cart_items');
Route::get('/api/restaurant/table/order/cart/{table_customer_id}', 'Restaurant_table_customer_controller@show');
Route::get('/api/restaurant/table/order/delete', 'Restaurant_table_customer_controller@delete');

Route::post('/api/restaurant/table/customer/add', 'Restaurant_table_customer_controller@store');
Route::post('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@bill_out');
Route::post('/api/restaurant/table/customer/bill/make/{id}', 'Restaurant_table_customer_controller@make_bill');
Route::get('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@show_temp_bill');
Route::get('/api/restaurant/table/customer/bill/view/{id}', 'Restaurant_table_customer_controller@show_bill');
Route::get('/api/restaurant/table/customer/bill/list/{id}', 'Restaurant_table_customer_controller@list_bill');
Route::get('/api/restaurant/table/customer/orders/{id}', 'Restaurant_table_customer_controller@show_order');
Route::get('/api/restaurant/table/customer/list', 'Restaurant_table_customer_controller@get_list');
Route::post('/api/restaurant/table/customer/remove/{id}', 'Restaurant_table_customer_controller@destroy');
Route::post('/api/restaurant/table/customer/payment/make/{id}', 'Restaurant_payment_controller@store');
Route::get('/api/restaurant/table/customer/payment/list/{id}', 'Restaurant_payment_controller@show');
Route::get('/api/restaurant/table/list/{type}', 'Restaurant_table_controller@get_list');

Route::get('/restaurant/order/{id}', 'Restaurant_order_controller@index')->middleware('logged');
Route::get('/restaurant/bill/{id}', 'Restaurant_bill_controller@index')->middleware('logged');




//api get
Route::get('/api/search/inventory/item/{type}/{option}', 'Inventory_item_controller@search_item');


Route::get('/api/purchases/cart', 'Purchases_controller@show_cart');
Route::get('/api/purchases/cart/show', 'Purchases_controller@cart');
Route::get('/api/inventory/item/show', 'Inventory_item_controller@show');


//Inventory
Route::get('/inventory', 'Inventory_item_controller@index')->middleware('logged');
Route::post('/api/inventory/item/add', 'Inventory_item_controller@store');

//Purchases
Route::get('/purchases', 'Purchases_controller@index')->middleware('logged');
Route::get('/purchases/view/{id}', 'Purchases_controller@show')->middleware('logged');
Route::post('/api/purchases/cart/item/add/{id}', 'Purchases_controller@store_cart');
Route::post('/api/purchases/cart/info', 'Purchases_controller@add_info_cart');
Route::post('/api/purchases/make', 'Purchases_controller@store_purchase');
Route::put('/api/purchases/cart/item/update/{id}', 'Purchases_controller@update_cart_items');
Route::delete('/api/purchases/cart/item/delete/{id}', 'Purchases_controller@delete_cart_items');
Route::delete('/api/purchases/cart/delete', 'Purchases_controller@destroy_cart');


//Issuances
Route::get('/issuance', 'Issuance_controller@index')->middleware('logged');
Route::get('/issuance/view/{id}', 'Issuance_controller@show')->middleware('logged');
Route::get('/api/issuance/cart', 'Issuance_controller@show_cart');
Route::post('/api/issuance/cart/item/add/{id}', 'Issuance_controller@store_cart');
Route::post('/api/issuance/cart/info', 'Issuance_controller@add_info_cart');
Route::post('/api/issuance/make', 'Issuance_controller@store_issuance');
Route::put('/api/issuance/cart/item/update/{id}', 'Issuance_controller@update_cart_items');
Route::delete('/api/issuance/cart/item/delete/{id}', 'Issuance_controller@delete_cart_items');
Route::delete('/api/issuance/cart/delete', 'Issuance_controller@destroy_cart');

//reports
Route::get('/reports', 'Reports_controller@index')->middleware('logged');
Route::get('/reports/view/{type}', 'Reports_controller@show')->middleware('logged');
Route::get('/reports/print/{type}', 'Reports_controller@show_print')->middleware('logged');
Route::get('/api/reports/general/{type}', 'Reports_controller@api');


//users
Route::get('/users', 'Users_controller@index')->middleware('logged');
Route::get('/login', 'Restaurant_controller@login');
Route::post('/login', 'Users_controller@login');
Route::get('/logout', 'Restaurant_controller@logout')->middleware('logged');


Route::get('/settings','Restaurant_controller@settings')->middleware('logged');


