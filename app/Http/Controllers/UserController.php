<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UserController extends Controller
{
	/*
	 *	Registra un usuario nuevo en la base de datos (desde mockAPI)
	 * 
	 *	@access public
	 *	@param json string
	 *	@return http status y json string de acuerdo a resultado
	 */
    public function new(Request $request)
    {
		$client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);
				
		$response = $client->request('GET', 'users', [
		    'query' => ['search' => $request->mail]
		]);

		$response = json_decode($response->getBody(), true);

		if(isset($response) && count($response)){
			return response()->json([
                'error' => 'Correo electrónico ya existe.'
            ], 400);
		} else {
			$data = array();
			$data["name"] = $request->name;
			$data["password"] = $request->password;
			$data["mail"] = $request->mail;
			
			$response = $client->request('POST', 'users', [
			    \GuzzleHttp\RequestOptions::JSON => $data
			]);
			
			$response = json_decode($response->getBody(), true);
			
			if(isset($response) && count($response) && $response["mail"] == $data["mail"]){
				return response()->json([
	                'success' => 'Usuario registrado con éxito.'
	            ], 201);
			} else {
				return response()->json([
	                'error' => 'Ocurrió error al registrar el usuario.'
	            ], 400);
			}
		}
    }
    
    /*
	 *	Handler de la sesión del usuario, depende del tipo de método del request
	 * 
	 *	@access restricted
	 *	@param json string with token (required)
	 *	@return http status y json string con información del usuario de acuerdo a resultado
	 */
    public function me(Request $request)
    {
		$client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);
		if ($request->isMethod('get')) {
			/*
			 *	Obtiene la información del usuario logueado
			 * 
			 *	@access restricted
			 *	@param json string with token (required)
			 *	@return http status y json string con información del usuario de acuerdo a resultado
			*/
			$response = $client->request('GET', 'users', [
			    'query' => ['search' => $request->auth_mail]
			]);
	
			$response = json_decode($response->getBody(), true);
	
			if(isset($response) && count($response)){
				$response = $response[0];
				unset($response["token"]);
			} else {
				return response()->json([
	                'error' => 'Usuario no existe.'
	            ], 404);
			}
	        
	        return response()->json($response, 201);
        } elseif ($request->isMethod('put')) {
	        /*
			 *	Actualiza la información del usuario logueado
			 * 
			 *	@access restricted
			 *	@param json string with token (required) and user data
			 *	@return http status y json string con información del usuario de acuerdo a resultado
			*/
	        $response = $client->request('GET', 'users', [
			    'query' => ['search' => $request->auth_mail]
			]);
	
			$response = json_decode($response->getBody(), true);
			
			if(isset($response) && count($response)){
				$response = $response[0];
				$data = array();
				$data["mail"] = $request->mail;
				$data["password"] = $request->password;
				$data["name"] = $request->name;
	
				$response = $client->request('PUT', 'users/'.($response["id"]), [
				    \GuzzleHttp\RequestOptions::JSON => $data
				]);
				return response()->json([
	                'success' => 'Datos de usuario actualizados.'
	            ], 201);
			} else {
				return response()->json([
	                'error' => 'Usuario no existe.'
	            ], 404);
			}  
	    } elseif ($request->isMethod('delete')) {
		    /*
			 *	Elimina al usuario logueado desde la base de datos (mockAPI)
			 * 
			 *	@access restricted
			 *	@param json string with token (required)
			 *	@return http status y json string con información del usuario de acuerdo a resultado
			*/
		    $response = $client->request('GET', 'users', [
		    	'query' => ['search' => $request->auth_mail]
			]);
	
			$response = json_decode($response->getBody(), true);
			
			if(isset($response) && count($response)){
				$response = $response[0];
				$data = array();
				$data["mail"] = $request->mail;
	
				$response = $client->request('DELETE', 'users/'.($response["id"]), [
				    \GuzzleHttp\RequestOptions::JSON => $data
				]);
				return response()->json([
	                'success' => 'Datos de usuario eliminados.'
	            ], 201);
			} else {
				return response()->json([
	                'error' => 'Usuario no existe.'
	            ], 404);
			}  
		}
    }
}