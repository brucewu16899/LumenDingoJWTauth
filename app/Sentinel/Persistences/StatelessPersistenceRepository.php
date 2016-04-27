<?php

namespace App\Sentinel\Persistences;

use Cartalyst\Sentinel\Persistences\PersistableInterface;
use Cartalyst\Sentinel\Persistences\PersistenceRepositoryInterface;

class StatelessPersistenceRepository implements PersistenceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function check()
    {
        // intentionally left blank
    }

    /**
     * {@inheritdoc}
     */
    public function findByPersistenceCode($code)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByPersistenceCode($code)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(PersistableInterface $persistable, $remember = false)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function persistAndRemember(PersistableInterface $persistable)
    {
        return $this->persist($persistable, true);
    }

    /**
     * {@inheritdoc}
     */
    public function forget()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove($code)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flush(PersistableInterface $persistable, $forget = true)
    {
        // intentionally left blank
    }
}
