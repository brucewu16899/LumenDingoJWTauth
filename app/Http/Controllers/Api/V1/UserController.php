<?php

//ref: https://lumen.laravel.com/docs/5.2/encryption
//ref: http://coderexample.com/restful-api-in-lumen-a-laravel-micro-framework/

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
//use App\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class UserController extends Controller
{
    /**
      * Store a secret message for the user.
      *
      * @param  Request  $request
      * @param  int  $id
      *
      * @return Response
      */
     public function index()
     {

        //$users  = User::all();
        $users = Sentinel::getUserRepository()->all();
     /*   foreach($users as $user) {
            $user->setHidden(['password']);
        } */
        return response()->json([
            'success' => ['message' => 'Users indexed.'],
            'users'   => $users,
        ]);
        //return response()->json($users);
     }

    public function get()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from token not found.');
            return response()->json(['error' => ['message' => 'User from token not found.']], 422);
        }

            /*
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

  */
      //  $user->setHidden(['password']);
        return response()->json([
            'success' => ['message' => 'User obtained.'],
            'user'    => $user,
        ]);
        //return response()->json($user);
    }

    public function getByID($id)
    {
        if (!$user = Sentinel::findUserById($id)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from ID not found.');
            return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
        }

        return response()->json([
            'success' => ['message' => 'User obtained.'],
            'user'    => $user,
        ]);
    }

    public function create(Request $request)
    {
        $defaultRoleSlug = 'consumer';

        $credentials = $request->only(['email', 'password']);

        $rules = [
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ];

        $messages = [
            'email.unique'   => 'unique',
            'email.email'    => 'email',
            'email.required' => 'required',

            'password.min'      => 'min:6',
            'password.required' => 'required',
        ];

        $validator = Validator::make($credentials, $rules, $messages);

        if ($validator->fails()) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('Input invalid.', $validator->errors());
            //return response()->json($validator->errors(), 422);
             return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
        }

        /*$credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];*/

        //create user and assign default role

        if (!$role = Sentinel::findRoleBySlug($defaultRoleSlug)) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Role from slug not found.');
            //return response()->json(['error' => ['message' => 'Role from slug not found.']], 422);
        }

        $user = Sentinel::registerAndActivate($credentials);
        if (!$user->inRole($role)) {
            $role->users()->attach($user);
        }

        return response()->json([
            'success' => ['message' => 'User created.'],
            'user'    => $user,
        ]);
        //return response()->json($user);
    }

    public function update(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$user = JWTAuth::parseToken()->authenticate()) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from token not found.');
            return response()->json(['error' => ['message' => 'User from token not found.']], 422);
        }

        $rules = [
            'email'    => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6',
        ];

        $messages = [
            'email.unique' => 'unique',
            'email.email'  => 'email',

            'password.min' => 'min:6',
        ];

        $validator = Validator::make($credentials, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //return response()->json($validator->errors());
        }

        function appendIfFilled($field, &$request, &$credentials)
        {
            if ($request->has($field)) {
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

        return response()->json([
            'success' => ['message' => 'User updated.'],
            'user'    => $user,
        ]);
        //return response()->json($user);
    }

    public function delete($id)
    {
        if (!$user = Sentinel::findUserById($id)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from ID not found.');
            return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
        }
        $user->delete();

        return response()->json(['success' => ['message' => 'User deleted.']]);
    }

    public function listRoles($id)
    {
        if (!$user = Sentinel::findById($id)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from ID not found.');
            return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
        }

        return response()->json([
            'success' => ['message' => 'Roles listed.'],
            'roles'   => $user->getRoles(),
        ]);
        //return response()->json( $user->getRoles());
    }

    public function assignRole($id, $roleid)
    {
        if (!$user = Sentinel::findById($id)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from ID not found.');
            return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
        }
        if (!$role = Sentinel::findRoleById($roleid)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('Role from ID not found.');
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
        }

        if (!$user->inRole($role)) {
            $role->users()->attach($user);
            if (!$user = Sentinel::findById($id)) { //update user variable after addign role
                return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
            }
        }

        //return response()->json( $user->getRoles());
        //return response()->json( $role->getUsers());

        return response()->json([
            'success' => ['message' => 'Role assigned.'],
            'roles'   => $user->getRoles(),
        ]);
        //return response()->json(['success' => 'role_assigned']);
    }

    public function removeRole($id, $roleid)
    {
        if (!$user = Sentinel::findById($id)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('User from ID not found.');
            return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
        }
        if (!$role = Sentinel::findRoleById($roleid)) {
            //throw new \Dingo\Api\Exception\StoreResourceFailedException('Role from ID not found.');
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
        }

        if ($user->inRole($role)) {
            $role->users()->detach($user);
            if (!$user = Sentinel::findById($id)) { //update user variable after addign role
                return response()->json(['error' => ['message' => 'User from ID not found.']], 422);
            }
        }

        return response()->json([
            'success' => ['message' => 'Role removed.'],
            'roles'   => $user->getRoles(),
        ]);
        //return response()->json(['success' => 'role_removed']);
    }
}
