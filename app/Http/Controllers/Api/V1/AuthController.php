<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
//use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AuthController extends Controller
{
    use Helpers;

    public function login(Request $request){

        $credentials = $request->only(['email', 'password']);

/*
        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //throw new ValidationHttpException($validator->errors()->all());
        }*/

         $rules = [ 
            'email' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => 'required',
            'password.required' => 'required',
        ];

        $validator = Validator::make($credentials, $rules, $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
        }

        try {
            //Sentinel stateless login
            $user = Sentinel::stateless($credentials); 
            if (! ($token = JWTAuth::attempt($credentials)) || ! $user) {
                return response()->json(['error' => ['message' => 'Credentials invalid.']], 401);
                //return $this->response->errorUnauthorized();
                //return response()->json(['error' => 'invalid_credentials'], 401);
            }
             Sentinel::login($user); 

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => ['message' => 'Failed to create token']], 500);
            //return $this->response->error('could_not_create_token', 500);
            //return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
            'success' => ['message' => 'User token created.'],
            'token' => $token,
        ]);
        //return response()->json([compact('token'), $user]);
        //return response()->json(compact('token'));
    }


    public function logout(){

        JWTAuth::invalidate(JWTAuth::getToken());

       /*try {
           JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return response()->json(['error' => 'token_blacklisted']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'token_absent']);
        }
*/
        return response()->json(['success' => ['message' => 'User token invalidated.']]);
        //return response()->json(['success' => 'logged out']);
    }
}