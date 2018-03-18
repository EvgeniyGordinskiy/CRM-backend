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
Auth::routes();

Route::get('auth/confirm/{token}', ['as' => 'auth.confirm', 'uses' => 'AuthController@confirmEmail']);
Route::post('auth', ['as' => 'auth.authenticate', 'uses' => 'AuthController@authenticate']);
Route::post('auth/register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);
Route::post('auth/reset_password', ['as' => 'auth.resetPassword', 'uses' => 'AuthController@resetPassword']);
Route::get('auth/change_password/{token}', ['as' => 'auth.changePassword', 'uses' => 'AuthController@changePassword']);

// Refresh token route
Route::get('refresh_token', [
    'as' => 'auth.refresh',
    'uses' => 'AuthController@refresh',
]);

// Account Route
Route::group([
    'as' => 'account.',
    'prefix' => 'account',
    'middleware' => [
        'jwt.auth'
    ],
], function () {
    Route::get('', ['as' => 'show', 'uses' => 'Account\AccountController@show']);

});

// Users
Route::group([
    'as' => 'users.',
    'prefix' => 'users',
    'middleware' => ['jwt.auth', 'jwt.subscribe'],
], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Account\UserController@index']);
    Route::get('{user}', ['as' => 'show', 'uses' => 'Account\UserController@show']);
    Route::post('', ['as' => 'store', 'uses' => 'Account\UserController@store']);
    Route::post('{user}', ['as' => 'update', 'uses' => 'Account\UserController@update']);
    Route::delete('{user}', ['as' => 'destroy', 'uses' => 'Account\UserController@destroy']);
});