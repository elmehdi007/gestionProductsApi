<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Enum\RoleIds;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private Request $request,
                                private $adminsRole=[]
                                ){
                                    $this->adminsRole=[RoleIds::Admin->value];
                                }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = $this->request->only(['email', 'password']);
        $response = null;

        try {
            
            $validator = Validator::make($this->request->all(), [
                'email' => 'required|min:2|max:250',
                'password' => 'required|min:2|max:250'
            ]);

            if ($validator->fails()) $response =response()->json($validator->errors(), 400);

            else if (! $token = auth()->attempt($credentials)) 
               $response = response()->json(['errors' => __('unauthorized')], 401);
            
            
            else $response = $this->respondWithToken($token);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error"=>[($this->env==="prod")?__('internal error'):$th->__toString()]]], 500);;
        }
        finally{
            return $response;
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $response = null;

        try {

            auth()->logout();
            $response = response()->json(['message' => __('successfully logged out')],200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error"=>[($this->env==="prod")?__('internal error'):$th->__toString()]]], 500);;
        }
        finally{
            return $response;
        }
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
       return $response = response()->json([
                'user' => User::find(auth()->user()->id),
                'access_token' => $token,
                'token_type' => 'bearer',
                //'expires_in' => auth()->factory()->getTTL() * 60
                'expires_in' => auth()->factory()->getTTL() * 60

        ],200);
    }
}