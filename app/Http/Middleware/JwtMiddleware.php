<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use GuzzleHttp\Client;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token');
        
        if(!$token) {
            return response()->json([
                'error' => 'Token no ingresada.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Token expirada.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error mientras se procesaba el token.'
            ], 400);
        }
        
        $client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);
				
		$response = $client->request('GET', 'users', [
		    'query' => ['search' => $token]
		]);

		$response = json_decode($response->getBody(), true);
        
        if(isset($response) && count($response) < 1){
			return response()->json([
                'error' => 'Token no válido.'
            ], 400);
		} else {
			$user_mail = $response[0]["mail"];
		}
        
        $request->auth_mail = $user_mail;
        return $next($request);
    }
}