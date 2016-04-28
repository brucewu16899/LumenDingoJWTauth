<?php

namespace App\Http\Throttles;

use Dingo\Api\Http\RateLimit\Throttle\Throttle;
use Illuminate\Container\Container;

class GroupThrottle extends Throttle
{
    /**
     * Group throttle will be matched unconditionally, including group of routes.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $app)
    {
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

        };
    }
}
