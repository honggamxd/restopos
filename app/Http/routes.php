
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
Route::get('/restaurant', 'Restaurant_controller@index');


//Restaurant Menu
Route::get('/restaurant/menu', 'Restaurant_menu_controller@index');
Route::post('/api/restaurant/menu', 'Restaurant_menu_controller@store');
Route::put('/api/restaurant/menu', 'Restaurant_menu_controller@update');
Route::get('/api/restaurant/menu/search/{type}', 'Restaurant_menu_controller@search');
Route::get('/api/restaurant/menu/subcategory', 'Restaurant_menu_controller@show_subcategory');

Route::get('/api/restaurant/menu/list/{type}', 'Restaurant_menu_controller@get_list');
Route::get('/api/restaurant/menu/category/{id}', 'Restaurant_menu_controller@show_category');
Route::put('/api/restaurant/menu/list/{id}', 'Restaurant_menu_controller@available_to_menu');

Route::post('/api/restaurant/table', 'Restaurant_table_controller@add_table');
Route::put('/api/restaurant/table', 'Restaurant_table_controller@edit_table');
Route::post('/api/restaurant/table/order/make/{id}', 'Restaurant_order_controller@store');
Route::post('/api/restaurant/server', 'Restaurant_controller@add_server');
Route::put('/api/restaurant/server', 'Restaurant_controller@edit_server');
Route::get('/api/restaurant/server/list', 'Restaurant_controller@show_server');
Route::post('/api/restaurant/name', 'Restaurant_controller@update');

Route::get('/api/restaurant/table/order/view/{id}', 'Restaurant_order_controller@show');
Route::post('/api/restaurant/table/order/cart', 'Restaurant_table_customer_controller@order_cart');
Route::post('/api/restaurant/table/order/cart/update/{type}/{id}', 'Restaurant_table_customer_controller@update_cart_item');
Route::get('/api/restaurant/table/order/cart/{table_customer_id}', 'Restaurant_table_customer_controller@show');
Route::get('/api/restaurant/table/order/delete', 'Restaurant_table_customer_controller@delete');
Route::post('/api/restaurant/table/order/remove', 'Restaurant_table_customer_controller@remove_cart_item');
Route::post('/api/restaurant/table/order/cancel/request/{type}', 'Restaurant_order_cancellation_controller@before_bill_out_cancellation_request');


Route::put('/api/restaurant/table/customer/update/{id}', 'Restaurant_table_customer_controller@update');
Route::post('/api/restaurant/table/customer/add', 'Restaurant_table_customer_controller@store');
Route::post('/api/restaurant/table/customer/bill/make/{id}', 'Restaurant_table_customer_controller@make_bill');
Route::post('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@bill_out');
Route::get('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@show_temp_bill');
Route::get('/api/restaurant/table/customer/bill/view/{id}', 'Restaurant_table_customer_controller@show_bill');
Route::get('/api/restaurant/table/customer/bill/list/{id}', 'Restaurant_table_customer_controller@list_bill');
Route::get('/api/restaurant/table/customer/orders/{id}', 'Restaurant_table_customer_controller@show_order');
Route::get('/api/restaurant/table/customer/list', 'Restaurant_table_customer_controller@get_list');
Route::post('/api/restaurant/table/customer/remove/{id}', 'Restaurant_table_customer_controller@destroy');
Route::post('/api/restaurant/table/customer/payment/make/{id}', 'Restaurant_payment_controller@store');
Route::get('/api/restaurant/table/customer/payment/list/{id}', 'Restaurant_payment_controller@show');
Route::get('/api/restaurant/table/list/{type}', 'Restaurant_table_controller@get_list');

Route::get('/restaurant/order/{id}', 'Restaurant_order_controller@index');
Route::get('/restaurant/cancellations', 'Restaurant_order_cancellation_controller@index');
Route::get('/restaurant/bill/{id}', 'Restaurant_bill_controller@index');
Route::get('/api/restaurant/orders/cancellations/show/{id?}', 'Restaurant_order_cancellation_controller@show');
Route::post('/api/restaurant/orders/cancellations/accept/{id?}', 'Restaurant_order_cancellation_controller@accept_request');
Route::post('/api/restaurant/orders/cancellations/delete/{id?}', 'Restaurant_order_cancellation_controller@delete_request');
Route::get('/api/restaurant/orders/cancellations/accept/{id}', 'Restaurant_order_cancellation_controller@accepted_request');
Route::post('/api/restaurant/orders/cancellations/settle/{id}', 'Restaurant_order_cancellation_controller@settlement');




//api get
Route::get('/api/search/inventory/item/{type}/{option}', 'Inventory_item_controller@search_item');


Route::get('/api/purchase/cart', 'Purchases_controller@show_cart');
Route::get('/api/purchase/cart/show', 'Purchases_controller@cart');
Route::get('/api/inventory/item/show', 'Inventory_item_controller@show');


//Inventory
Route::get('/inventory', 'Inventory_item_controller@index');
Route::get('/inventory/item/{id}', 'Inventory_item_controller@index_item_history');
Route::post('/api/inventory/item/add', 'Inventory_item_controller@store');
Route::get('/api/inventory/item/history/{id}', 'Inventory_item_controller@show_item_history');

//Restaurant Inventory
Route::get('/restaurant/inventory', 'Restaurant_inventory_controller@index');
Route::get('/api/restaurant/inventory/items', 'Restaurant_inventory_controller@show_items');


//Purchases
Route::get('/purchase', 'Purchases_controller@index');
Route::get('/purchase/view/{id}', 'Purchases_controller@show');
Route::post('/api/purchase/cart/item/add/{id}', 'Purchases_controller@store_cart');
Route::post('/api/purchase/cart/info', 'Purchases_controller@add_info_cart');
Route::post('/api/purchase/make', 'Purchases_controller@store_purchase');
Route::put('/api/purchase/cart/item/update/{id}', 'Purchases_controller@update_cart_items');
Route::delete('/api/purchase/cart/item/delete/{id}', 'Purchases_controller@delete_cart_items');
Route::delete('/api/purchase/cart/delete', 'Purchases_controller@destroy_cart');


//Issuances
Route::get('/issuance', 'Issuance_controller@index');
Route::get('/issuance/view/{id}', 'Issuance_controller@show');
Route::get('/api/issuance/cart', 'Issuance_controller@show_cart');
Route::post('/api/issuance/cart/item/add/{id}', 'Issuance_controller@store_cart');
Route::post('/api/issuance/cart/info', 'Issuance_controller@add_info_cart');
Route::post('/api/issuance/make', 'Issuance_controller@store_issuance');
Route::put('/api/issuance/cart/item/update/{id}', 'Issuance_controller@update_cart_items');
Route::delete('/api/issuance/cart/item/delete/{id}', 'Issuance_controller@delete_cart_items');
Route::delete('/api/issuance/cart/delete', 'Issuance_controller@destroy_cart');

//reports
Route::get('/reports', 'Reports_controller@index');
Route::get('/restaurant/reports', 'Reports_controller@restaurant');
Route::get('/restaurant/reports/print', 'Reports_controller@restaurant_print');


Route::get('/reports/view/{type}', 'Reports_controller@show');
Route::get('/reports/print/{type}', 'Reports_controller@show_print');
Route::get('/api/reports/general/purchases', 'Reports_controller@purhcased_item');
Route::get('/api/reports/general/issuances', 'Reports_controller@issued_items');
Route::get('/api/reports/general/f_and_b', 'Reports_controller@f_and_b');
Route::get('/api/reports/general/menu_popularity', 'Reports_controller@menu_popularity');



//users
Route::get('/users', 'Users_controller@index');
Route::get('/login', 'Users_controller@login_index');
Route::post('/login', 'Users_controller@login');
Route::get('/logout', 'Users_controller@logout');
Route::get('/api/users', 'Users_controller@show_users');
Route::post('/api/users/add', 'Users_controller@add');


Route::get('/restaurant/settings','Restaurant_controller@settings');


