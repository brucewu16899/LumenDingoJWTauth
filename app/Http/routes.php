<?php


$app->get('/', function () use ($app) {
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {

  //  $api->group(['prefix' => 'auth'], function () use ($api) {
   // });

    $api->post('auth', 'App\Http\Controllers\Api\V1\AuthController@login');
    $api->get('auth', 'App\Http\Controllers\Api\V1\AuthController@logout');

	$api->get('users', 'App\Http\Controllers\Api\V1\UserController@index');
    //$api->delete('users', 'App\Http\Controllers\Api\V1\UserController@delete');

    $api->post('user', 'App\Http\Controllers\Api\V1\UserController@create');
    $api->group(['middleware' => 'jwt.auth'], function ($api) {
        // Endpoints registered here will have the "foo" middleware applied.
        $api->get('user', 'App\Http\Controllers\Api\V1\UserController@get');
	    $api->put('user', 'App\Http\Controllers\Api\V1\UserController@update');
    });

    $api->get('users/{id}/role', 'App\Http\Controllers\Api\V1\UserController@obtainRole');
    $api->post('users/{id}/role/{roleid}', 'App\Http\Controllers\Api\V1\UserController@assignRole');
    $api->delete('users/{id}/role/{roleid}', 'App\Http\Controllers\Api\V1\UserController@removeRole');

    $api->get('roles', 'App\Http\Controllers\Api\V1\RoleController@index');
    $api->post('role', 'App\Http\Controllers\Api\V1\RoleController@create');
    $api->get('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@get');
    $api->put('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@update');
    $api->delete('role/{id}', 'App\Http\Controllers\Api\V1\RoleController@delete');

    $api->get('perm/{id}', 'App\Http\Controllers\Api\V1\PermissionController@index');
    $api->post('perm/{id}', 'App\Http\Controllers\Api\V1\PermissionController@add');
    $api->delete('perm/{id}', 'App\Http\Controllers\Api\V1\PermissionController@remove');

});