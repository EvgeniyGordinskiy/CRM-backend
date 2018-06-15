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

Route::group([
    'prefix' => 'social/'
], function(){
    Route::get('{provider}', 'Auth\AuthSocialController@redirect');
    Route::get('callback', 'Auth\AuthSocialController@handle');
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


//Authenticate routes
Route::group(['middleware' => ['jwt.auth']], function () {
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
        Route::group(['middleware' => ['jwt.subscribe']], function () {
            Route::post('', ['as' => 'store', 'uses' => 'UserController@store']);
            Route::post('{user}', ['as' => 'update', 'uses' => 'UserController@update']);
            Route::delete('{user}', ['as' => 'destroy', 'uses' => 'UserController@destroy']);
        });
    });
});