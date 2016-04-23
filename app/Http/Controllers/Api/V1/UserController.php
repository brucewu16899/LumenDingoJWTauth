<?php

//ref: https://lumen.laravel.com/docs/5.2/encryption
//ref: http://coderexample.com/restful-api-in-lumen-a-laravel-micro-framework/

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Store a secret message for the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */


     public function index(){
  
        $users  = User::all();

     /*   foreach($users as $user) {
            $user->setHidden(['password']);
        } */
        return response()->json($users);
  
    }

    public function get($id){
  
        $user  = User::find($id);
  
       // $user->setHidden(['password']);
        return response()->json($user);
    }
  
    public function create(Request $request){
  
        //$user = User::create($request->all());
       /* $user = new User;
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();*/

        $rules = [ 
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

        $messages = [
            'email.unique' => 'unique',
            'email.required' => 'required',
            'email.email' => 'email',
            
            'password.required' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }
            


        $user = Sentinel::registerAndActivate([
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ]);

      //  $user->setHidden(['password']);
        return response()->json($user);

    }
  
    public function delete($id){
        $user  = User::find($id);
        $user->delete();
 
        return response()->json('deleted');
    }
  
    public function update(Request $request){

        $user = JWTAuth::parseToken()->authenticate();

        $rules = [ 
            'email' => 'email|unique:users,email,'. $user->id,
            'password' => '',
        ];

        $messages = [
            'email.unique' => 'unique',
            'email.email' => 'email',
            
            //'password.required' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }
            
        function appendIfFilled($field, &$request, &$credentials){
            if($request->has($field)){
                $credentials += [$field => $request->input($field)]; 
            }
        }

        $credentials = [];
        appendIfFilled('email', $request, $credentials);
        appendIfFilled('password', $request, $credentials);

/*
        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];
*/

        

        $user = Sentinel::update($user, $credentials);  

/*
        $user  = User::find($id);
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
  */

       // $user->setHidden(['password']);
        return response()->json($user);
    }
}