<?php


namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Validator;

use Tymon\JWTAuth\Facades\JWTAuth;



class PermissionController extends Controller
{
    /**
     * Store a secret message for the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */


    public function index($id){
  
        if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'not_found']);
        }
  
       // $user->setHidden(['password']);
        return response()->json($role->permissions);
    }
  
    public function add(Request $request, $id){

        if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'not_found']);
        }
        
        $rules = [ 
            'permission' => 'required',
        ];

        $messages = [
            'permission.required' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }

        $role->addPermission($request->input('permission'));
        $role->save();
        return response()->json($role->permissions);

    }
  
    public function remove(Request $request, $id){
         if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'not_found']);
        }
        
        $rules = [ 
            'permission' => 'required',
        ];

        $messages = [
            'permission.required' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }

        $role->updatePermission($request->input('permission'));
        $role->save();

        return response()->json($role->permissions);
    }
  
    
}