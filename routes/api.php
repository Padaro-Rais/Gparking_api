<?php

use Illuminate\Http\Request;
// CategorieProduitController


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

$v1Prefix = 'v1/';
$MPrefix = $v1Prefix.'mobile/';

Route::group(['middleware' => 'api', 'prefix' => $v1Prefix.'auth'], function () {

    //Login User
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('me', 'Auth\AuthController@me');
    Route::post('register', 'Auth\AuthController@register');
});

Route::group(['prefix' => $MPrefix], function () {

    /**
     * Auth Mobile
     */

    Route::post('auth/loginMobile', 'Auth\AuthController@loginMobile');
    Route::post('auth/loginPhoneMobile', 'Auth\AuthController@loginPhoneMobile');
});

Route::post('loginMobile', 'Auth\logMobileController@login');
Route::apiResource('typeclients', 'Api\ClientController');
Route::apiResource('transactions', 'Api\TransactionController');
Route::get('mytransactions/{id}', 'Api\TransactionController@clienttransaction');


Route::group(['middleware' => 'jwt', 'prefix' => $v1Prefix], function () {

    // CRUD Base
    Route::apiResource('users', 'Api\UserController');

    Route::apiResource('parkings', 'Api\ParkingController');

    Route::apiResource('agents', 'Api\AgentController');

    Route::apiResource('clients', 'Api\ClientController');

    Route::apiResource('transactions', 'Api\TransactionController');

    Route::apiResource('entrepriseparking', 'Api\EntrepriseParkingController');


    Route::get('clientparking/{id}', 'Api\EntrepriseParkingController@clientparking');
    Route::get('clientagent/{id}', 'Api\AgentController@clientagent');
    Route::get('clienttransaction/{id}', 'Api\TransactionController@clienttransaction');


    Route::get('option/parking/{id}', 'Api\optionController@parking');

    Route::get('option/entreprise', 'Api\optionController@entreprise');






});

Route::fallback(function(){
    return response()->json(['error' => 'Route Not Found'], 404);
});
