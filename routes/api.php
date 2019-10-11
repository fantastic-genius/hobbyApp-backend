<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function(){
    Route::post('register', 'Api\AuthController@register');
    Route::post('login', 'Api\AuthController@login');
    Route::prefix('hobby')->group(function(){
        Route::middleware('auth:api')->group(function(){
            Route::post('/', 'Api\HobbyController@create');
            Route::patch('/{id}', 'Api\HobbyController@update');
            Route::get('/', 'Api\HobbyController@getAUserHobbies');
            Route::get('/{id}', 'Api\HobbyController@get');
            Route::delete('/{id}', 'Api\HobbyController@delete');
        });
    });
});
