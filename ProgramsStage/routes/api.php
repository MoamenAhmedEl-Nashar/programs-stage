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


Route::group(['middleware' => ['cors', 'json.response']], function () {
// public routes
    Route::get('/home', 'ProgramController@index')->name('program.index');
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
    Route::get('/links/download/{id}', 'LinkController@downloadFile')->name('download.links');
});

Route::middleware('auth:api')->group(function(){
    // our routes to be protected will go in here
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
    Route::post('/programs/create', 'ProgramController@store')->name('store.programs');
    Route::post('/programs/edit/{id}', 'ProgramController@update')->name('update.programs');
    Route::delete('/programs/delete/{id}', 'ProgramController@destroy')->name('delete.programs');

    Route::post('/links/create', 'LinkController@store')->name('store.links');
    Route::delete('/links/delete/{id}', 'LinkController@destroy')->name('delete.links');

});


