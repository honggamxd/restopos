<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'Restaurant_controller@index');
Route::get('/restaurant', 'Restaurant_controller@index')->middleware('auth.level.2');


//Restaurant Menu
Route::get('/restaurant/menu', 'Restaurant_menu_controller@index')->middleware('auth.level.3');
Route::post('/api/restaurant/menu', 'Restaurant_menu_controller@store');
Route::put('/api/restaurant/menu', 'Restaurant_menu_controller@update');
Route::get('/api/restaurant/menu/search/{type}', 'Restaurant_menu_controller@search');
Route::get('/api/restaurant/menu/subcategory', 'Restaurant_menu_controller@show_subcategory');
Route::get('/api/restaurant/menu/subcategory/list', 'Restaurant_menu_controller@list_subcategory');

Route::get('/api/restaurant/menu/list/{type}', 'Restaurant_menu_controller@get_list');
Route::get('/api/restaurant/menu/category/{id}', 'Restaurant_menu_controller@show_category')->middleware('auth.level.4');
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
Route::post('/api/restaurant/table/order/cancel/request/{type}', 'Restaurant_order_cancellation_controller@store_cancellation_request');


Route::put('/api/restaurant/table/customer/update/{id}', 'Restaurant_table_customer_controller@update');
Route::post('/api/restaurant/table/customer/add', 'Restaurant_table_customer_controller@store');
Route::post('/api/restaurant/table/customer/bill/make/{id}', 'Restaurant_bill_controller@make_bill');
Route::post('/api/restaurant/table/customer/bill/delete/{id}', 'Restaurant_bill_controller@delete');
Route::post('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@bill_out');
Route::get('/api/restaurant/table/customer/bill/preview/{id}', 'Restaurant_table_customer_controller@show_temp_bill');
Route::get('/api/restaurant/table/customer/bill/view/{id}', 'Restaurant_bill_controller@show_bill');
Route::put('/api/restaurant/table/customer/bill/view/{id}', 'Restaurant_bill_controller@update_invoice');
Route::get('/api/restaurant/table/customer/bill/invoice-number-logs/{id}', 'Restaurant_bill_controller@show_invoice_number_logs');
Route::get('/api/restaurant/table/customer/bill/list/{id}', 'Restaurant_bill_controller@list_bill');
Route::get('/api/restaurant/table/customer/orders/{id}', 'Restaurant_table_customer_controller@show_order');
Route::get('/api/restaurant/table/customer/list', 'Restaurant_table_customer_controller@get_list');
Route::post('/api/restaurant/table/customer/remove/{id}', 'Restaurant_table_customer_controller@destroy');
Route::post('/api/restaurant/table/customer/payment/make/{id}', 'Restaurant_payment_controller@store');
Route::get('/api/restaurant/table/customer/payment/list/{id}', 'Restaurant_payment_controller@show');
Route::get('/api/restaurant/table/list/{type}', 'Restaurant_table_controller@get_list');

Route::get('/restaurant/order/{id}', 'Restaurant_order_controller@index');
Route::get('/restaurant/cancellations', 'Restaurant_order_cancellation_controller@index')->middleware('auth.level.3');
Route::get('/restaurant/bill/{id}', 'Restaurant_bill_controller@index');
Route::get('/restaurant/bill/{id}/edit', 'Restaurant_bill_controller@edit')->middleware('auth.level.4');
Route::put('/restaurant/bill/{id}/edit', 'Restaurant_bill_controller@update');
Route::get('/api/restaurant/orders/cancellations/show/{id?}', 'Restaurant_order_cancellation_controller@show');
Route::get('/api/restaurant/orders/cancellations/view/{id}', 'Restaurant_order_cancellation_controller@show_data');
Route::post('/api/restaurant/orders/cancellations/accept/{id?}', 'Restaurant_order_cancellation_controller@accept_request');
Route::post('/api/restaurant/orders/cancellations/delete/{id?}', 'Restaurant_order_cancellation_controller@delete_request');
Route::get('/api/restaurant/orders/cancellations/accept/{id}', 'Restaurant_order_cancellation_controller@accepted_request');
Route::post('/api/restaurant/orders/cancellations/settle/{id}', 'Restaurant_order_cancellation_controller@settlement');
Route::post('/api/restaurant/orders/user-cancellations/delete/{id}', 'Restaurant_order_cancellation_controller@user_delete_request');




//api get
Route::get('/api/search/inventory/item/{type}/{option}', 'Inventory_item_controller@search_item');

Route::get('/api/inventory/item/show', 'Inventory_item_controller@show');


//Inventory
Route::get('/inventory', 'Inventory_item_controller@index')->middleware('auth.level.5');
Route::get('/inventory/item/{id}', 'Inventory_item_controller@index_item_history')->middleware('auth.level.5');
Route::post('/api/inventory/item/add', 'Inventory_item_controller@store');
Route::get('/api/inventory/item/history/{id}', 'Inventory_item_controller@show_item_history');

//reports
Route::get('/reports', 'Reports_controller@index')->middleware('auth.level.5');
Route::get('/restaurant/reports', 'Reports_controller@restaurant');
Route::get('/restaurant/orders', 'Reports_controller@orders')->middleware('auth.level.4');


Route::get('/reports/view/{type}', 'Reports_controller@show')->middleware('auth.level.5');
Route::get('/reports/print/{type}', 'Reports_controller@show_print')->middleware('auth.level.5');
Route::get('/api/reports/general/orders', 'Reports_controller@get_orders_list');
Route::get('/api/reports/general/purchases', 'Reports_controller@purhcased_item');
Route::get('/api/reports/general/issuances', 'Reports_controller@issued_items');
Route::get('/api/reports/general/f_and_b', 'Reports_controller@f_and_b');
Route::get('/api/reports/general/f_and_b_export', 'Reports_controller@f_and_b_export');
Route::get('/api/reports/general/menu_popularity', 'Reports_controller@menu_popularity');
Route::get('/api/reports/general/menu_popularity_export', 'Reports_controller@menu_popularity_export');



//users
Route::get('/users', 'Users_controller@index')->middleware('auth.level.5');
Route::get('/account-settings', 'Users_controller@settings');
Route::get('/login', 'Users_controller@login_index')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Users_controller@logout')->name('logout');
Route::get('/api/users', 'Users_controller@show_users');
Route::post('/api/users/add', 'Users_controller@add');
Route::post('/api/users/settings', 'Users_controller@save_settings');
Route::post('/api/users/edit/{id}', 'Users_controller@edit_privilege');
Route::post('/api/users/delete/{id}', 'Users_controller@delete');


Route::get('/restaurant/settings','Restaurant_controller@settings')->middleware('auth.level.4');
Route::get('/clean_bill_detail','Users_controller@clean_bill_detail');
Route::get('/restaurant_payment','Users_controller@restaurant_payment');
Route::get('/bill_test','Restaurant_bill_controller@test');
Route::get('/purchase-request.pdf','Users_controller@purchase_request');
Route::get('/purchase-order.pdf','Users_controller@purchase_order');
Route::get('/receiving-report.pdf','Users_controller@receiving_report');
Route::get('/stock-issuance.pdf','Users_controller@stock_issuance');
Route::get('/request-to-canvass.pdf','Users_controller@request_to_canvass');

Route::get('/inventory/items','Inventory\Item_controller@index')->name('inventory.item.index');
Route::get('/api/inventory/items/item','Inventory\Item_controller@list')->name('api.inventory.item.index');
Route::post('/api/inventory/items/item','Inventory\Item_controller@store')->name('api.inventory.item.store');
Route::put('/api/inventory/items/item/{id}','Inventory\Item_controller@update')->name('api.inventory.item.update');
Route::delete('/api/inventory/items/item/{id}','Inventory\Item_controller@destroy')->name('api.inventory.item.delete');


Route::get('/inventory/purchase-request/create','Inventory\Purchase_request_controller@index')->name('inventory.purchase-request.create');
Route::get('/inventory/purchase-request/list','Inventory\Purchase_request_controller@list')->name('inventory.purchase-request.list');
Route::get('/api/inventory/purchase-request/show','Inventory\Purchase_request_controller@show')->name('inventory.purchase-request.show');
Route::post('/api/inventory/purchase-request/add','Inventory\Purchase_request_controller@store')->name('inventory.purchase-request.store');
Route::put('/api/inventory/purchase-request/update/{id}','Inventory\Purchase_request_controller@update')->name('inventory.purchase-request.update');
Route::put('/api/inventory/purchase-request/delete/{id}','Inventory\Purchase_request_controller@delete')->name('inventory.purchase-request.delete');
