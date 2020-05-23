<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('exchange/{amount}/{from}/{to}', 'ForexController@convert');

Route::get('exchange/info', 'ForexController@info');

Route::get('cache/clear', 'ForexController@clearCache');

Route::fallback(function(){
    return response()->json(['error' => 1, 'msg' => 'invalid request'], 404);
});
