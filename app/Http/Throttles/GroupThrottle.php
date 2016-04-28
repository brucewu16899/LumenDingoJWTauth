<?php

use Illuminate\Container\Container;
use Dingo\Api\Http\RateLimit\Throttle\Throttle;

class GroupThrottle extends Throttle
{

    /**
     * Group throttle will be matched unconditionally, including group of routes.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $app){

        return true;
    }
}