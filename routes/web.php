<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $result = [
        'httpcode'  =>  200,
        'error'     =>  false,
        'data'      =>  [
            'message'   =>'Welcome to Service API',
        ]
    ];
    return response()->json($result,200);
});
