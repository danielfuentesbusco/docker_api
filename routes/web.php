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

/*
	Se define relación del endpoint login a AuthController metodo authenticate
	No requiere autenticación
*/

$router->post('api/login', ['uses' => 'AuthController@authenticate']);

/*
	Se define relación del endpoint new a UserController metodo new
	No requiere autenticación
*/	

$router->post('api/new', ['uses' => 'UserController@new']);

/*
	Se define relación del endpoint new a UserController metodo new
	Requiere autenticación, pasa por middleware middleware
*/

$router->group(
    ['middleware' => 'jwt.auth'], 
    function() use ($router) {
	    
	    /*
			Se define relación del endpoint me a UserController metodo new
			Requiere autenticación, se accede solo al validar token de acceso
		*/
		
		// Metodo get define acceso a información propia
        $router->get('api/me', ['uses' => 'UserController@me']);
        
        // Metodo put define actualización de información
        $router->put('api/me', ['uses' => 'UserController@me']);

		// Metodo delete define eliminación de infomración
        $router->delete('api/me', ['uses' => 'UserController@me']);
    }
);

