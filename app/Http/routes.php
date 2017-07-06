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

Route::get('/', function () {
    return view('sample');
});

Route::get('/order', function () {
    return view('order');
});

Route::get('/bill', function () {
    return view('billout');
});

Route::get('/billperitem', function () {
    return view('billoutperitem');
});

Route::get('/inventory', function () {
    return view('inventory');
});


Route::get('/receiving', function () {
    return view('receiving');
});



Route::get('/reports', function () {
    return view('reports');
});



