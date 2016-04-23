<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AuthController extends Controller
{
    use Helpers;

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        try {
            //Sentinel stateless login
            $user = Sentinel::stateless($credentials); 
             Sentinel::login($user); 
            if (! $token = JWTAuth::attempt($credentials)) {
                //return $this->response->errorUnauthorized();
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
           // return $this->response->error('could_not_create_token', 500);
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user->setHidden(['password']);
        return response()->json([compact('token'), $user]);
    }
}