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

Route::group([
    'prefix' => 'auth'
], function(){
    Route::post('', ['as' => '', 'uses' => 'AuthController@authenticate']);
    Route::post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
    Route::post('reset_password', ['as' => 'resetPassword', 'uses' => 'AuthController@resetPassword']);
    Route::get('change_password/{token}', ['as' => 'changePassword', 'uses' => 'AuthController@changePassword']);
});
//Route::get('/confirm/email/send', ['as' => 'email.send', 'uses' => 'EmailConfirmationController@send']);

Route::group([
    'as' => 'confirm.',
    'prefix' => 'confirm',
], function(){

    Route::group([
        'as' => 'email.',
        'prefix' => 'email',
    ],function(){
        Route::get('send', ['as' => 'send', 'uses' => 'EmailConfirmationController@send'])->middleware('jwt.auth');
        Route::get('{token}', ['as' => 'confirm', 'uses' => 'EmailConfirmationController@confirm']);
    });
});


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
    Route::get('', ['as' => 'index', 'uses' => 'UserController@index']);
    Route::get('{user}', ['as' => 'show', 'uses' => 'UserController@show']);
    Route::post('', ['as' => 'store', 'uses' => 'UserController@store']);
    Route::post('{user}', ['as' => 'update', 'uses' => 'UserController@update']);
    Route::delete('{user}', ['as' => 'destroy', 'uses' => 'UserController@destroy']);
});