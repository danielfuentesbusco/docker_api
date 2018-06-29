<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class AuthController extends BaseController 
{
    private $request;
 
    public function __construct(Request $request) {
        $this->request = $request;
    }
 
	/*
	 *	Genera el token de acceso con la librería JWT
	 * 
	 *	@access protected
	 *	@param string $user_id
	 *	@return token de acceso
	*/
    protected function jwt($user_id) {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user_id,
            'iat' => time(),
            'exp' => time() + 60*60
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    
    /*
	 *	Genera el token de acceso con la librería JWT
	 * 
	 *	@access public
	 *	@param string $mail, string $password
	 *	@return token de acceso
	*/    
	public function authenticate(User $user) {
        $this->validate($this->request, [
            'mail'     => 'required|email',
            'password'  => 'required'
        ]);
        
        $client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);

		$response = $client->request('GET', 'users', [
		    'query' => ['search' => $this->request->mail]
		]);

		$response = json_decode($response->getBody(), true);
        
        if(isset($response) && count($response) < 1){
            return response()->json([
                'error' => 'Correo electrónico no existe.'
            ], 400);
        }
        
        // Verificamos validez del password y generamos el token de acceso
        if (md5($this->request->password) == md5($response[0]["password"])) {
	        $token = $this->jwt($response[0]["id"]);
	        
	        $data = array();
			$data["token"] = $token;
			
			$response = $client->request('PUT', 'users/'.($response[0]["id"]), [
			    \GuzzleHttp\RequestOptions::JSON => $data
			]);
	        
            return response()->json([
                'token' => $token
            ], 200);
        }
        
        return response()->json([
            'error' => 'Password incorrecto.'
        ], 400);
    }
}