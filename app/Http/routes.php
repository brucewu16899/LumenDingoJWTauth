<?php


$app->get('/', function () use ($app) {
    //return $app->version();
    $throttle = new GroupThrottle(['limit' => 100]);

    return $throttle->getLimit();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

  //  $api->group(['prefix' => 'auth'], function () use ($api) {
   // });

    ///////////////////////// OPEN /////////////////////////

    //$api->group(['middleware' => 'api.throttle',  'limit' => 10, 'expires' => 1], function ($api) {
    //$api->group(['middleware' => 'api.throttle', 'throttle' => 'GroupThrottle'], function ($api) {
    $api->group(['middleware' => 'api.throttle'], function ($api) {
        $api->post('auth', 'App\Http\Controllers\Api\V1\AuthController@login');            // Authenticate

        $api->get('auth', 'App\Http\Controllers\Api\V1\AuthController@logout');            // Deauthenticate
        $api->group(['middleware' => 'jwt.renew'], function ($api) {
            $api->get('auth/refresh', function () {
                 return response()->json(['success' => ['message' => 'User token refreshed.']]);
            });
        });

        ///////// 	 		User		 /////////
        $api->post('user', 'App\Http\Controllers\Api\V1\UserController@create');        // Register account

        $api->group(['middleware' => 'jwt.auth'], function ($api) {
            $api->get('user', 'App\Http\Controllers\Api\V1\UserController@get');        // OBTAIN user info by token
            $api->put('user', 'App\Http\Controllers\Api\V1\UserController@update');        // EDIT user info by token
        });

        ///////////////////////// ADMINISTRATIVE /////////////////////////
        $api->group(['middleware' => ['jwt.auth', 'role:admin']], function ($api) {
            ///////// 	 		User		 /////////
            $api->get('user/index', 'App\Http\Controllers\Api\V1\UserController@index');    // LIST all users
            $api->get('user/{id}', 'App\Http\Controllers\Api\V1\UserController@getByID');    // OBTAIN user info by ID
            $api->delete('user/{id}', 'App\Http\Controllers\Api\V1\UserController@delete');    // DELETE user by ID

            //////// 	 		User Roles		 /////////
            $api->get('user/{id}/role/list', 'App\Http\Controllers\Api\V1\UserController@listRoles');            // LIST user roles
            $api->post('user/{id}/role/{roleid}', 'App\Http\Controllers\Api\V1\UserController@assignRole');        // ASSIGN user role
            $api->delete('user/{id}/role/{roleid}', 'App\Http\Controllers\Api\V1\UserController@removeRole');    // REMOVE user role

            //////// 	 		 Roles		 /////////
            $api->get('role/index', 'App\Http\Controllers\Api\V1\RoleController@index');                // LIST all roles
            $api->post('role', 'App\Http\Controllers\Api\V1\RoleController@create');                    // CREATE a role
            $api->get('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@get');                    // OBTAIN role info by ID
            $api->put('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@update');                // EDIT role by ID
            $api->delete('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@delete');                // DELETE role by ID
            $api->get('role/{id}/perm', 'App\Http\Controllers\Api\V1\RoleController@listPerms');        // LIST all role permissions
            $api->post('role/{id}/perm', 'App\Http\Controllers\Api\V1\RoleController@assignPerm');        // ASSIGN role permission
            $api->delete('role/{id}/perm', 'App\Http\Controllers\Api\V1\RoleController@removePerm');    // REMOVE role permission
        });

    });

});
