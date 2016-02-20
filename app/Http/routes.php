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

    Route::get('/home', 'HomeController@index'); //Home Page

    //Syndie Stuff
    Route::group(['prefix' => 'syndie'], function () {
        //Contract DB
        Route::group(['prefix' => 'contracts','middleware' => 'auth'], function () {
            Route::get('', ['as' => 'syndie.contracts.index', 'uses'=>'ContractController@index']);
            Route::get('/add', ['as' => 'syndie.contracts.add.get', 'uses'=>'ContractController@getAdd']);
            route::post('/add', ['as' => 'syndie.contracts.add.post', 'uses'=>'ContractController@postAdd']);
            Route::get('/{contract}/show', ['as' => 'syndie.contracts.show.get', 'uses'=>'ContractController@getShow']);
            Route::get('/{contract}/edit', ['as' => 'syndie.contracts.edit.get', 'uses'=>'ContractController@getEdit']);
            Route::get('/{contract}/accept', ['as' => 'syndie.contracts.accept.get', 'uses'=>'ContractController@getAccept']);
            Route::get('/{contract}/complete', ['as' => 'syndie.contracts.complete.get', 'uses'=>'ContractController@getComplete']);
            Route::get('/{contract}/confirm', ['as' => 'syndie.contracts.confirm.get', 'uses'=>'ContractController@getConfirm']);
        });
    });
});
