<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/admin/home/{subs?}', 'Admin\\HomeController@index')->name('admin-home')->where(['subs' => '.*']);
Route::get('/admin/client-auth', 'Admin\\OAuthController@index')->name('admin-oauth');

Route::get('/home', 'BookmarksController@index')->name('home');

Route::group(['prefix' => 'private/api/v1/bookmarks'], function (\Illuminate\Routing\Router $router) {
    $router->get('{page_id}', 'Admin\\BookmarksApiController@getBookmarkChildren');
    $router->get('{page_id}/tree', 'Admin\\BookmarksApiController@getBookmarkTree');
});