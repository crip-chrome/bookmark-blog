<?php

Route::get('/user', 'UserController@index');

Route::group(['prefix' => 'bookmarks'], function (\Illuminate\Routing\Router $router) {
    $router->post('created', 'BookmarksController@created');
    $router->post('changed', 'BookmarksController@changed');
    $router->post('moved', 'BookmarksController@moved');
    $router->post('removed', 'BookmarksController@removed');
});
