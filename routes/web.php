<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', 'SiteController@live')->name('home');


Route::get('versions/{id}/json',[
    'as'=>'versions.json',
    'uses'=> 'VersionController@showJson'
]);

Route::match(['put','patch'], 'versions/{id}/restore', 'VersionController@restore')->name('versions.restore');
Route::resource('versions', 'VersionController', ['only' => ['index', 'store', 'destroy']]);

Route::get('sites/live', 'SiteController@live')->name('sites.live');
Route::get('sites/demo', 'SiteController@demo')->name('sites.demo');
Route::get('sites/{id}/config', 'SiteController@getConfig')->name('sites.getConfig');
Route::get('sites/{id}/comments', 'SiteController@getComments')->name('sites.getComments');
Route::post('sites/import', 'SiteController@import')->name('sites.import');
Route::post('sites/upgrade', 'SiteController@upgrade')->name('sites.upgrade');
Route::get('sites/{site}/setLive', 'SiteController@setLive')->name('sites.setLive');
Route::resource('sites', 'SiteController', ['only' => ['store','show']]);

Route::match(['put','patch'], 'users/{id}/resetpassword', 'UserController@resetpassword')->name('users.resetpassword');
Route::resource('users', 'UserController', ['only' => ['index', 'store']]);

Route::resource('comments', 'CommentController', ['only' => ['store']]);


// Two Factor Authentication
Route::get('2fa', 'TwoFactorController@showTwoFactorForm');
Route::post('2fa', 'TwoFactorController@verifyTwoFactor');