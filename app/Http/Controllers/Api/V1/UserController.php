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

    public function get(){
  
       try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return response()->json(['error' => 'token_blacklisted']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'token_absent']);
        }
  
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
            'password' => 'required|min:6',
        ];

        $messages = [
            'email.unique' => 'unique',
            'email.required' => 'required',
            'email.email' => 'email',
            
            'password.required' => 'required',
            'password.min' => 'min:6',
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
 
        return response()->json(['success' => 'deleted']);
    }
  
    public function update(Request $request){


      // $user = JWTAuth::parseToken()->authenticate();

       try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return response()->json(['error' => 'token_blacklisted']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'token_absent']);
        }

    // the token is valid and we have found the user via the sub claim
    //return response()->json(compact('user'));



        $rules = [ 
            'email' => 'email|unique:users,email,'. $user->id,
            'password' => 'min:6',
        ];

        $messages = [
            'email.unique' => 'unique',
            'email.email' => 'email',
            
            'password.min' => 'min:6',
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


    public function obtainRole($id){
        
       
        if(! $user = Sentinel::findById($id)){
            return response()->json(['error' => 'user_not_found']);
        }



        return response()->json( $user->getRoles());

    }

    public function assignRole($id, $roleid){
        
       
        if(! $user = Sentinel::findById($id)){
            return response()->json(['error' => 'user_not_found']);
        }
        if(! $role = Sentinel::findRoleById($roleid)){
            return response()->json(['error' => 'role_not_found']);
        }


        if(!$user->inRole($role)){
            $role->users()->attach($user);
        }


        //return response()->json( $user->getRoles());
        //return response()->json( $role->getUsers());

        return response()->json(['success' => 'role_assigned']);
    }

     public function removeRole($id, $roleid){
        
       
        if(! $user = Sentinel::findById($id)){
            return response()->json(['error' => 'user_not_found']);
        }
        if(! $role = Sentinel::findRoleById($roleid)){
            return response()->json(['error' => 'role_not_found']);
        }

        if($user->inRole($role)){
            $role->users()->detach($user);
        }

        return response()->json(['success' => 'role_removed']);
    }
  
}