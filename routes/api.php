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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('user-register', 'API\APIUsersController@usersSave');
Route::get('email-verification/{token}', 'API\APIUsersController@usersActivation');
Route::get('users-list', 'API\APIUsersController@usersList');


Route::group(['middleware' => 'auth:api'], function() {
	Route::post('login', 'API\APILoginController@login');
	Route::put('user-changepassword/{id}', 'API\APIUsersController@changePassword');
});