<?php

use App\Http\Middleware\VerifyJWTToken;
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

Route::group([
    'prefix' => 'auth'
], function(){
    Route::post('', ['as' => '', 'uses' => 'AuthController@authenticate']);
    Route::post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
    Route::post('reset_password', ['as' => 'resetPassword', 'uses' => 'AuthController@resetPassword']);
    Route::get('change_password/{token}', ['as' => 'changePassword', 'uses' => 'AuthController@changePassword']);
    Route::get('refresh_token', ['as' => 'auth.refresh', 'uses' => 'AuthController@refresh']);
});

Route::group([
    'prefix' => 'social/'
], function(){
    Route::get('{provider}', 'Auth\AuthSocialController@redirect');
    Route::get('callback', 'Auth\AuthSocialController@handle');
});

//Authenticate routes
Route::group(['middleware' => ['jwt.auth']], function () {
    Route::group([
        'as' => 'password.',
        'prefix' => 'password',
        ], function(){
        Route::post('reset', ['as' => 'reset', 'uses' => 'AuthController@resetPassword'])->middleware(VerifyJWTToken::class);
        Route::get('change', ['as' => 'change', 'uses' => 'AuthController@changePassword']);
    });
    // Clients
    Route::group([
        'as' => 'clients.',
        'prefix' => 'clients',
    ], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ClientController@index']);
        Route::get('{user}', ['as' => 'show', 'uses' => 'ClientController@show']);
        Route::group(['middleware' => ['jwt.subscribe']], function () {
            Route::post('', ['as' => 'store', 'uses' => 'ClientController@store']);
            Route::post('{user}', ['as' => 'update', 'uses' => 'ClientController@update']);
            Route::delete('{user}', ['as' => 'destroy', 'uses' => 'ClientController@destroy']);
        });
    });

    // Users
    Route::group([
        'as' => 'users.',
        'prefix' => 'users',
        'middleware' => ['jwt.auth', 'jwt.subscribe'],
    ], function () {
        Route::get('', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::get('{user}', ['as' => 'show', 'uses' => 'UserController@show']);
        Route::post('', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::post('{user}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::delete('{user}', ['as' => 'destroy', 'uses' => 'UserController@destroy']);
    });
});