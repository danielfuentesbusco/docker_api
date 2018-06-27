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
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt($user_id) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user_id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'mail'     => 'required|email',
            'password'  => 'required'
        ]);
        
        // Find the user by email
     
        
        $client = new \GuzzleHttp\Client(['base_uri' => env('MOCKAPI_URL')]);
				
		$response = $client->request('GET', 'users', [
		    'query' => ['search' => $this->request->mail]
		]);

		$response = json_decode($response->getBody(), true);
        
        if(isset($response) && count($response) < 1){
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            return response()->json([
                'error' => 'Correo electrónico no existe.'
            ], 400);
        }
        
        // Verify the password and generate the token
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
        // Bad Request response
        return response()->json([
            'error' => 'Password incorrecto.'
        ], 400);
    }
}