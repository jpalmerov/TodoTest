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

# --- user ---

$router->post('user/login', 'UserController@login');

$router->post('user/logout', 'UserController@login');

$router->post('user/create', 'UserController@create');

# --- todo ---

$router->post('todo/create', 'TodoController@create');

$router->post('todo/delete', 'TodoController@delete');

$router->post('todo/update', 'TodoController@update');

$router->post('todo/list', 'TodoController@list');

$router->post('todo/get', 'TodoController@get');

# --- todo_item ---

$router->post('todo_item/create', 'TodoController@createItem');

$router->post('todo_item/list', 'TodoController@listItems');

$router->post('todo_item/delete', 'TodoController@deleteItem');

$router->post('todo_item/update', 'TodoController@updateItem');

$router->post('todo_item/update_status', 'TodoController@updateItemStatus');
