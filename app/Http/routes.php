<?php

$app->get('/', function () use ($app) {
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {

    $api->post('auth/login', 'App\Http\Controllers\Api\V1\AuthController@login');


    $api->get('auth/login', function(){
    	return 'hello';
    });

    
	$api->get('users', 'App\Http\Controllers\Api\V1\UserController@index');
    $api->post('users', 'App\Http\Controllers\Api\V1\UserController@create');
	$api->get('user', 'App\Http\Controllers\Api\V1\UserController@get');
	//$api->put('users/{id}', 'App\Http\Controllers\Api\V1\UserController@update');
	//$api->delete('users/{id}', 'App\Http\Controllers\Api\V1\UserController@delete');
    $api->put('user', 'App\Http\Controllers\Api\V1\UserController@update');

  $api->group(['middleware' => 'jwt.auth'], function ($api) {
        // Endpoints registered here will have the "foo" middleware applied.

	$api->put('users/{id}', 'App\Http\Controllers\Api\V1\UserController@update');
	$api->delete('users/{id}', 'App\Http\Controllers\Api\V1\UserController@delete');
    });

});