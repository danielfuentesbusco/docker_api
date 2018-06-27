<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UserController extends Controller
{
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
    
    
    
    public function me(Request $request)
    {
		$client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);
				
		if ($request->isMethod('get')) {
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