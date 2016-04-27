<?php

namespace App;

use Cartalyst\Sentinel\Users\EloquentUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends EloquentUser implements JWTSubject
{
    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getUserId();
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
