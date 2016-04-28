<?php

namespace App\Http\Throttles;

use Dingo\Api\Http\RateLimit\Throttle\Throttle;
use Illuminate\Container\Container;

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

        return true;
    }

    /**
     * Define throttle groups conditonally.
     *
     * @return void
     */
    public function group()
    {
        return function ($app, $request) {

            //return null to apply thottle globally
            return md5($request->path());
        };
    }
}
