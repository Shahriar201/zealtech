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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Auth routes
Route::group([
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::post('password_generate', 'AuthController@password_generate');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');
    });

});

//Product Routes
Route::group([
    'middleware' => 'auth:api',
], function() {
    Route::post('getProducts', 'ProductController@getProducts');
    Route::post('createProduct', 'ProductController@createProduct');
    Route::post('updateProduct', 'ProductController@updateProduct');
    Route::post('deleteProduct', 'ProductController@deleteProduct');
});
