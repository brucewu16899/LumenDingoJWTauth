<?php


namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Validator;

use Tymon\JWTAuth\Facades\JWTAuth;

use DB;


class RoleController extends Controller
{
    /**
     * Store a secret message for the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */


    public function index(){
  
        $roles = $users = DB::table('roles')->get();

        return response()->json($roles);
  
    }

    public function get($id){
  
        if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'role_not_found']);
        }
  
       // $user->setHidden(['password']);
        return response()->json($role);
    }
  
    public function create(Request $request){

        $rules = [ 
            'name' => 'required|unique:roles',
            'slug' => 'required|unique:roles',
        ];

        $messages = [
            'name.unique' => 'unique',
            'name.required' => 'required',
            
            'slug.unique' => 'unique',
            'slug.required' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }

        $role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ]);

        return response()->json($role);

    }
  
    public function delete($id){
        if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'role_not_found']);
        }
  
        $role->delete();
 
        return response()->json(['success' => 'deleted']);
    }
  
    public function update(Request $request, $id){
  
        if( ! $role = Sentinel::findRoleById($id)){
            return response()->json(['error' => 'role_not_found']);
        }

        $rules = [ 
            'name' => 'unique:roles,name,'. $role->id,
            'slug' => 'unique:roles,slug,'. $role->id,
        ];

        $messages = [
            'name.unique' => 'unique',
            'slug.unique' => 'unique',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()){
            return response()->json($validator->errors());
        }
            
        if($request->has('name')){
                $role->name = $request->input('name');
        }

        if($request->has('slug')){
                $role->slug = $request->input('slug');
        }

        $role->save();


        return response()->json($role);
    }
}