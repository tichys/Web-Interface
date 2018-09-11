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
Route::group(['prefix' => 'server', 'middleware' => 'auth'], function () {
    Route::get('/live/faxmachines', ['as' => 'api.server.live.get.faxmachines', 'uses' => 'Server\LiveController@getFaxmachines']);
    Route::get('/live/ghosts', ['as' => 'api.server.live.get.ghosts', 'uses' => 'Server\LiveController@getGhosts']);
    Route::post('/live/sendfax', ['as' => 'api.server.live.post.sendfax', 'uses' => 'Server\LiveController@postSendfax']);
    Route::post('/live/sendreport', ['as' => 'api.server.live.post.sendreport', 'uses' => 'Server\LiveController@postSendreport']);
    Route::post('/live/grantrespawn', ['as' => 'api.server.live.post.grantrespawn', 'uses' => 'Server\LiveController@postGrantrespawn']);
});