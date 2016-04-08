<?php

//Copyright (c) 2016 "Werner Maisl", "Sierra Brown"
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
            Route::get('/{contract}/delete', ['as' => 'syndie.contracts.deletecontract', 'uses'=>'Syndie\ContractController@deleteContract']);
            Route::post('/{contract}/addmessage',['as' => 'syndie.contracts.addmessage', 'uses'=>'Syndie\ContractController@addMessage']); //Add message to contract
            Route::get('/data',['as' => 'syndie.contracts.data', 'uses'=>'Syndie\ContractController@getContractData']);
            Route::get('/{contract}/subscribe', ['as' => 'syndie.contracts.subscribe', 'uses'=>'Syndie\ContractController@subscribe']);
            Route::get('/{contract}/unsubscribe', ['as' => 'syndie.contracts.unsubscribe', 'uses'=>'Syndie\ContractController@unsubscribe']);
        });

        Route::group(['prefix' => 'comments'],function(){
            Route::get('/{comment}/confirm', ['as' => 'syndie.contracts.confirm', 'uses'=>'Syndie\ContractController@confirm']); //Confirm Completion of the Contract
            Route::get('/{comment}/reopen', ['as' => 'syndie.contracts.reopen', 'uses'=>'Syndie\ContractController@reopen']); // Reopen the contract
            Route::get('/{comment}/delete',['as' => 'syndie.contracts.deletemessage', 'uses'=>'Syndie\ContractController@deleteMessage']); //Delete a comment
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

        Route::group(['prefix' => 'player'], function () {
            Route::get('', ['as' => 'admin.players.index', 'uses'=>'Admin\PlayerController@index']);
            Route::get('/{player_id}/show', ['as' => 'admin.players.show', 'uses'=>'Admin\PlayerController@show']);
            Route::get('/{player_id}/add_whitelist/{whitelist}', ['as' => 'admin.players.whitelist.add', 'uses'=>'Admin\PlayerController@addWhitelist']);
            Route::get('/{player_id}/remove_whitelist/{whitelist}', ['as' => 'admin.players.whitelist.remove', 'uses'=>'Admin\PlayerController@removeWhitelist']);
            Route::get('/{player_id}/warnings_data', ['as' => 'admin.players.warnings.data', 'uses'=>'Admin\PlayerController@getPlayerWarningsData']);
            Route::get('/{player_id}/notes_data', ['as' => 'admin.players.notes.data', 'uses'=>'Admin\PlayerController@getPlayerNotesData']);
            Route::get('/data', ['as' => 'admin.players.data', 'uses'=>'Admin\PlayerController@getPlayerData']);
        });

        Route::group(['prefix' => 'form'], function () {
            Route::get('', ['as' => 'admin.forms.index', 'uses'=>'Admin\FormController@index']);
            Route::get('/{form_id}/edit', ['as' => 'admin.forms.edit.get', 'uses'=>'Admin\FormController@getEdit']);
            Route::post('/{form_id}/edit', ['as' => 'admin.forms.edit.post', 'uses'=>'Admin\FormController@postEdit']);
            Route::get('/{form_id}/delete', ['as' => 'admin.forms.delete', 'uses'=>'Admin\FormController@delete']);
            Route::get('/add', ['as' => 'admin.forms.add.get', 'uses'=>'Admin\FormController@getAdd']);
            Route::post('/add', ['as' => 'admin.forms.add.post', 'uses'=>'Admin\FormController@postAdd']);
            Route::get('/data', ['as' => 'admin.forms.data', 'uses'=>'Admin\FormController@getFormData']);
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('', ['as' => 'admin.roles.index', 'uses'=>'Admin\RoleController@index']);
            Route::get('/add', ['as' => 'admin.roles.add.get', 'uses'=>'Admin\RoleController@getAdd']);
            Route::post('/add', ['as' => 'admin.roles.add.post', 'uses'=>'Admin\RoleController@postAdd']);
            Route::get('{role_id}/edit', ['as' => 'admin.roles.edit.get', 'uses'=>'Admin\RoleController@getEdit']);
            Route::post('{role_id}/edit', ['as' => 'admin.roles.edit.post', 'uses'=>'Admin\RoleController@postEdit']);
            Route::get('{role_id}/delete', ['as' => 'admin.roles.delete', 'uses'=>'Admin\RoleController@delete']);
            Route::post('{role_id}/addperm', ['as' => 'admin.roles.addperm', 'uses'=>'Admin\RoleController@addPermission']);
            Route::post('{role_id}/remperm', ['as' => 'admin.roles.remperm', 'uses'=>'Admin\RoleController@removePermission']);
            Route::post('{role_id}/adduser', ['as' => 'admin.roles.adduser', 'uses'=>'Admin\RoleController@addUser']);
            Route::post('{role_id}/remuser', ['as' => 'admin.roles.remuser', 'uses'=>'Admin\RoleController@removeUser']);
        });
    
        // CCIA Stuff
        Route::group(['prefix' => 'ccia', 'middleware' => 'auth'], function () {
            Route::group(['prefix' => 'generalnotice'], function () {
                Route::get('', ['as' => 'ccia.generalnotice.index', 'uses' => 'CCIA\GeneralNoticeController@index']);
                Route::get('/{generalnotice_id}/edit', ['as' => 'ccia.generalnotice.edit.get', 'uses' => 'CCIA\GeneralNoticeController@getEdit']);
                Route::post('/{generalnotice_id}/edit', ['as' => 'ccia.generalnotice.edit.post', 'uses' => 'CCIA\GeneralNoticeController@postEdit']);
                Route::get('/{generalnotice_id}/delete', ['as' => 'ccia.generalnotice.delete', 'uses' => 'CCIA\GeneralNoticeController@delete']);
                Route::get('/add', ['as' => 'ccia.generalnotice.add.get', 'uses' => 'CCIA\GeneralNoticeController@getAdd']);
                Route::post('/add', ['as' => 'ccia.generalnotice.add.post', 'uses' => 'CCIA\GeneralNoticeController@postAdd']);
                Route::get('/data', ['as' => 'ccia_generalnotice.data', 'uses'=>'CCIA\GeneralNoticeController@getData']);
            });
        });
    });
});
