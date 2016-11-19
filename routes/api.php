<?php

Route::get('/user', 'UserController@index');

Route::group(['prefix' => 'bookmarks'], function (\Illuminate\Routing\Router $router) {
    $router->post('created', 'BookmarksController@created');
    $router->post('edited', 'BookmarksController@changed');
    $router->post('changed-position', 'BookmarksController@moved');
    $router->post('moved-away', 'BookmarksController@removed');
    $router->post('sync', 'BookmarksController@sync');
});
