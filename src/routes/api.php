<?php

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

Route::get('city/filter', 'CityController@filter')->name('city.filter');

Route::apiResource('city', 'CityController', [
    'parameters' => ['city' => 'id']
]);

Route::get('state/filter', 'StateController@filter')->name('state.filter');

Route::apiResource('state', 'StateController', [
    'parameters' => ['state' => 'id']
]);