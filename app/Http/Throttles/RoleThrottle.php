<?php

namespace App\Http\Throttles;

use Dingo\Api\Http\RateLimit\Throttle\Throttle;
use Illuminate\Container\Container;

class RoleThrottle extends Throttle
{
    /**
     * Array of throttle options.
     *
     * @var array
     */
    protected $options = ['slugOrID' => ''];

    /**
     * Group throttle will be matched unconditionally, including group of routes.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $app)
    {
        $roleMatch = false;
        try {
            if ($user = $app['tymon.jwt.auth']->parseToken()->authenticate()) {
                $roleMatch = $user->inRole($this->options['slugOrID']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        }

        return $roleMatch;
    }

    /**
     * Define throttle groups conditonally.
     *
     * @return void
     */
    public function group()
    {
        return function ($app, $request) {
            return md5($request->path());
        };
    }

    /**
     * Set throttle options.
     *
     * @param array
     */
    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }
}
