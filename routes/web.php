<?php

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

$router->post(
    'api/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);

$router->group(
    ['middleware' => 'jwt.auth'], 
    function() use ($router) {
        $router->get('api/me', ['uses' => 'UserController@me']);
        $router->put('api/me', ['uses' => 'UserController@me']);
        $router->delete('api/me', ['uses' => 'UserController@me']);
    }
);

$router->post('api/new', ['uses' => 'UserController@new']);