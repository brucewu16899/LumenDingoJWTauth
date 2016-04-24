<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*$app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
	'jwt.auth' => 'Tymon\JWTAuth\Middleware\Authenticate',
    'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
 ]);
 */

 $app->routeMiddleware([
    'role' => App\Http\Middleware\RoleMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\StatelessSentinelServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
$app->register(Barryvdh\Cors\LumenServiceProvider::class);

if ( ! $app->environment('production')) {
    $app->register(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
}


//////////// DINGO API Exception handlers    //////////////
use Illuminate\Http\Response;

$app['Dingo\Api\Exception\Handler']->setErrorFormat([
    'error' => [
        'message' => ':message',
        'errors' => ':errors',
        // 'code' => ':code',
        //'status_code' => ':status_code',
        'debug' => ':debug'
    ]
]);

/*app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
    return ['error' => ['message' => 'route_not_found']];
});*/

app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\BadRequestHttpException $exception) {
    return ['error' => ['message' => $exception->getMessage()]];
});

app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
    return ['error' => ['message' => $exception->getMessage()]];
});



///////// Possible JWT-errors ///// 
/*
\Tymon\JWTAuth\Exceptions\InvalidClaimException
'Invalid value provided for claim "'.$this->getName().'"

Tymon\JWTAuth\Exception\JWTException
'An error occurred'
'The token could not be parsed from the request'
'A token is required'
'Could not create token: '

Symfony\Component\HttpKernel\Exception\BadRequestHttpException
'Token not provided'

Tymon\JWTAuth\Exceptions\TokenBlacklistedException
'The token has been blacklisted'

Tymon\JWTAuth\Exceptions\TokenInvalidException
'Could not decode token: '
'Token Signature could not be verified.'
'JWT payload does not contain the required claims'
'Not Before (nbf) timestamp cannot be in the future'
'Issued At (iat) timestamp cannot be in the future'
'Wrong number of segments'
'Malformed token'

\Tymon\JWTAuth\Exceptions\TokenExpiredException
'Token has expired'
'Token has expired and can no longer be refreshed'


*/

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
