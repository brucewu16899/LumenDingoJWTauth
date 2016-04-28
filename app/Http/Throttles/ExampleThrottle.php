<?php

use Illuminate\Container\Container;
use Dingo\Api\Http\RateLimit\Throttle\Throttle;

class ExampleThrottle extends Throttle
{
	
    /**
     * Example throttle will be matched unconditionally.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $app)
    {
        // Perform some logic here and return either true or false depending on whether
        // your conditions matched for the throttle.

        /*
			Usage in route:

			$api->version('v1', ['prefix' => 'api', 'middleware' => 'api.throttle', 'throttle' => 'ExampleThrottle'], function($api) {
			    $api->get('/', function () {
			        return ['foo' => 'bar'];
			    });

			    $api->get('users', function () {
			        return ['foo' => 'bar'];
			    });
			});

			Usage in bootstrap/app.php

			app('Dingo\Api\Http\RateLimit\Handler')->extend(new ExampleThrottle(['limit' => 200, 'expires' => 10]));

			Run: composer dump-autoload everytime a thrtottle is created

        */
        return true;
    }
}