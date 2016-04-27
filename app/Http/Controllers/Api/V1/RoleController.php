<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use DB;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    /**
     * Store a secret message for the user.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function index()
    {
        $roles = DB::table('roles')->get();

        return response()->json([
            'success' => ['message' => 'Roles indexed.'],
            'roles'   => $roles,
        ]);
        //return response()->json($roles);
    }

    public function get($id)
    {
        if (!$role = Sentinel::findRoleById($id)) {
            //return response()->json(['error' => 'role_not_found']);
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
        }

       // $user->setHidden(['password']);
        return response()->json([
            'success' => ['message' => 'Role obtained.'],
            'role'    => $role,
        ]);
        //return response()->json($role);
    }

    public function create(Request $request)
    {
        $roleRequest = $request->only(['name', 'slug']);

        $rules = [
            'name' => 'required|unique:roles',
            'slug' => 'required|unique:roles',
        ];

        $messages = [
            'name.unique'   => 'unique',
            'name.required' => 'required',

            'slug.unique'   => 'unique',
            'slug.required' => 'required',
        ];

        $validator = Validator::make($roleRequest, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //return response()->json($validator->errors());
        }

        $role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ]);

        $roles = DB::table('roles')->get();

        return response()->json([
            'success' => ['message' => 'Role created.'],
            'roles'   => $roles,
        ]);
        //return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $roleRequest = $request->only(['name', 'slug']);

        if (!$role = Sentinel::findRoleById($id)) {
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
            //return response()->json(['error' => 'role_not_found']);
        }

        $rules = [
            'name' => 'unique:roles,name,'.$role->id,
            'slug' => 'unique:roles,slug,'.$role->id,
        ];

        $messages = [
            'name.unique' => 'unique',
            'slug.unique' => 'unique',
        ];

        $validator = Validator::make($roleRequest, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //return response()->json($validator->errors());
        }

        if ($request->has('name')) {
            $role->name = $request->input('name');
        }

        if ($request->has('slug')) {
            $role->slug = $request->input('slug');
        }

        $role->save();

        $roles = DB::table('roles')->get();

        return response()->json([
            'success' => ['message' => 'Role updated.'],
            'roles'   => $roles,
        ]);
        //return response()->json($role);
    }

    public function delete($id)
    {
        if (!$role = Sentinel::findRoleById($id)) {
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
            //return response()->json(['error' => 'role_not_found']);
        }

        $role->delete();

        //return response()->json(['success' => ['message' => 'Role deleted.']]);
        $roles = DB::table('roles')->get();

        return response()->json([
            'success' => ['message' => 'Role deleted.'],
            'roles'   => $roles,
        ]);
        //return response()->json(['success' => 'deleted']);
    }

    public function listPerms($id)
    {
        if (!$role = Sentinel::findRoleById($id)) {
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
            //return response()->json(['error' => 'not_found']);
        }

       // $user->setHidden(['password']);
        return response()->json([
            'success'     => ['message' => 'Permissions listed.'],
            'permissions' => $role->permissions,
        ]);
        //return response()->json($role->permissions);
    }

    public function assignPerm(Request $request, $id)
    {
        $permRequest = $request->only(['permission']);

        if (!$role = Sentinel::findRoleById($id)) {
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
            //return response()->json(['error' => 'not_found']);
        }

        $rules = [
            'permission' => 'required',
        ];

        $messages = [
            'permission.required' => 'required',
        ];

        $validator = Validator::make($permRequest, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //return response()->json($validator->errors());
        }

        $role->addPermission($request->input('permission'));
        $role->save();

        return response()->json([
            'success'     => ['message' => 'Permission assigned.'],
            'permissions' => $role->permissions,
        ]);
        //return response()->json($role->permissions);
    }

    public function removePerm(Request $request, $id)
    {
        $permRequest = $request->only(['permission']);

        if (!$role = Sentinel::findRoleById($id)) {
            return response()->json(['error' => ['message' => 'Role from ID not found.']], 422);
            //return response()->json(['error' => 'not_found']);
        }

        $rules = [
            'permission' => 'required',
        ];

        $messages = [
            'permission.required' => 'required',
        ];

        $validator = Validator::make($permRequest, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error'     => ['message' => 'Input invalid.'],
                'validator' => $validator->errors(),
            ]);
            //return response()->json($validator->errors());
        }

        $role->removePermission($request->input('permission'));
        $role->save();

        return response()->json([
            'success'     => ['message' => 'Permission removed.'],
            'permissions' => $role->permissions,
        ]);
        //return response()->json($role->permissions);
    }
}
