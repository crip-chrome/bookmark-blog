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

use Illuminate\Routing\Router;

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/admin/home/{subs?}', 'Admin\\HomeController@index')->name('admin-home')->where(['subs' => '.*']);
Route::get('/admin/{subs?}', 'Admin\\HomeController@index')->name('admin')->where(['subs' => '.*']);

Route::get('/home', 'BookmarksController@index')->name('home');
Route::get('/author/{author_id}', 'BookmarksController@author')->name('author');
Route::get('/tag/{tag_id}', 'BookmarksController@tag')->name('tag');
Route::get('/category/{category_id}', 'BookmarksController@category')->name('category');

Route::group(['prefix' => 'private/api/v1'], function (Router $router) {
    $router->group(['prefix' => 'bookmarks'], function (Router $router) {
        $router->get('{page_id}', 'Admin\\BookmarksApiController@getBookmark');
        $router->post('{page_id}', 'Admin\\BookmarksApiController@saveBookmark');
    });

    $router->get('categories/options', 'Admin\\CategoriesApiController@options');
    $router->resource('categories', 'Admin\\CategoriesApiController', ['only' => ['index', 'store']]);
});