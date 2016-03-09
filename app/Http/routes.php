<?php

//Copyright (c) 2016 "Werner Maisl"
//
//This file is part of the Aurora Webinterface
//
//The Aurora Webinterface is free software: you can redistribute it and/or modify
//it under the terms of the GNU Affero General Public License as
//published by the Free Software Foundation, either version 3 of the
//License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Affero General Public License for more details.
//
//You should have received a copy of the GNU Affero General Public License
//along with this program. If not, see <http://www.gnu.org/licenses/>.

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => 'web'], function () {

    Route::group(['middleware' => 'guest'],function(){
        Route::get('/', function () {
            return view('welcome');
        });
    });

    Route::auth(); //Auth Routes

    //Route for SSO
    Route::any('/login/sso_server/', ['as' => 'login.sso', 'uses'=>'Auth\AuthController@sso_server']);

    Route::get('/home', 'HomeController@index'); //Home Page

    //Syndie Stuff
    Route::group(['prefix' => 'syndie','middleware' => 'auth'], function () {
        //Contract DB
        Route::group(['prefix' => 'contracts'], function () {
            Route::get('', ['as' => 'syndie.contracts.index', 'uses'=>'Syndie\ContractController@index']);
            Route::get('/add', ['as' => 'syndie.contracts.add.get', 'uses'=>'Syndie\ContractController@getAdd']);
            route::post('/add', ['as' => 'syndie.contracts.add.post', 'uses'=>'Syndie\ContractController@postAdd']);
            Route::get('/{contract}/show', ['as' => 'syndie.contracts.show', 'uses'=>'Syndie\ContractController@show']);
            Route::get('/{contract}/edit', ['as' => 'syndie.contracts.edit.get', 'uses'=>'Syndie\ContractController@getEdit']);
            Route::post('/{contract}/edit', ['as' => 'syndie.contracts.edit.post', 'uses'=>'Syndie\ContractController@postEdit']);
            Route::get('/{contract}/approve', ['as' => 'syndie.contracts.approve', 'uses'=>'Syndie\ContractController@approve']); //Mod Approve the contract
            Route::get('/{contract}/reject', ['as' => 'syndie.contracts.reject', 'uses'=>'Syndie\ContractController@reject']); //Mod Reject the contract
            Route::post('/{contract}/addmessage',['as' => 'syndie.contracts.addmessage', 'uses'=>'Syndie\ContractController@addMessage']); //Add message to contract
            Route::get('/{comment}/confirm', ['as' => 'syndie.contracts.confirm', 'uses'=>'Syndie\ContractController@confirm']); //Confirm Completion of the Contract
            Route::get('/{comment}/reopen', ['as' => 'syndie.contracts.reopen', 'uses'=>'Syndie\ContractController@reopen']); // Reopen the contract
            Route::get('/{comment}/delete',['as' => 'syndie.contracts.deletemessage', 'uses'=>'Syndie\ContractController@deleteMessage']); //Delete a comment
            Route::get('/data',['as' => 'syndie.contracts.data', 'uses'=>'Syndie\ContractController@getContractData']);
        });
    });

    //User Stuff
    Route::group(['prefix' => 'user','middleware' => 'auth'], function () {
        //User Dashboard
        Route::get('/', ['as' => 'user.dashboard', 'uses'=>'User\DashboardController@index']);
        //User Linking
        Route::group(['prefix' => 'link'], function () {
            Route::get('/', ['as' => 'user.link', 'uses'=>'User\LinkController@index']);
            Route::post('/add', ['as' => 'user.link.add', 'uses'=>'User\LinkController@add']);
            Route::get('/cancel', ['as' => 'user.link.cancel', 'uses'=>'User\LinkController@cancel']);
        });
        Route::get('/warnings', ['as' => 'user.warnings', 'uses'=>'User\WarningController@index']);

    });

    //Admin Stuff
    Route::group(['prefix' => 'admin','middleware' => 'auth'], function () {

        Route::group(['prefix' => 'stats'], function () {
            Route::get('', ['as' => 'admin.stats.index', 'uses'=>'Admin\StatsController@index']);
        });

        Route::group(['prefix' => 'whitelist'], function () {
            Route::get('', ['as' => 'admin.whitelist.index', 'uses'=>'Admin\WhitelistController@index']);
            Route::get('/{player_id}/show', ['as' => 'admin.whitelist.show', 'uses'=>'Admin\WhitelistController@show']);
            Route::get('/{player_id}/add/{whitelist}', ['as' => 'admin.whitelist.add', 'uses'=>'Admin\WhitelistController@add']);
            Route::get('/{player_id}/remove/{whitelist}', ['as' => 'admin.whitelist.remove', 'uses'=>'Admin\WhitelistController@remove']);
            Route::get('/data', ['as' => 'admin.whitelist.data', 'uses'=>'Admin\WhitelistController@getWhitelistData']);
        });

        Route::group(['prefix' => 'form'], function () {
            Route::get('', ['as' => 'admin.form.index', 'uses'=>'Admin\FormController@index']);
            Route::get('/{form_id}/edit', ['as' => 'admin.form.edit.get', 'uses'=>'Admin\FormController@getEdit']);
            Route::post('/{form_id}/edit', ['as' => 'admin.form.edit.post', 'uses'=>'Admin\FormController@postEdit']);
            Route::get('/{form_id}/delete', ['as' => 'admin.form.delete', 'uses'=>'Admin\FormController@delete']);
            Route::get('/add', ['as' => 'admin.form.add.get', 'uses'=>'Admin\FormController@getAdd']);
            Route::post('/add', ['as' => 'admin.form.add.post', 'uses'=>'Admin\FormController@postAdd']);
            Route::get('/data', ['as' => 'admin.form.data', 'uses'=>'Admin\FormController@getFormData']);
        });
    });
});
