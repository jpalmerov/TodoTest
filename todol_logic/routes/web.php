<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('user/login', 'UserController@login');

$router->post('user/create', 'UserController@create');

$router->post('todo/create', 'TodoController@create');

$router->post('todo/update', 'TodoController@update');

$router->post('todo/delete', 'TodoController@delete');

$router->post('todo/add_item', 'TodoController@addItem');

$router->post('todo/items', 'TodoController@items');

$router->post('todo/delete_item', 'TodoController@deleteItem');

$router->post('todo/update_item', 'TodoController@updateItem');

$router->post('todo/item', 'TodoController@item');
