<?php

namespace MenaraSolutions\Geographer\Traits;

/**
 * Class HasCollection
 * @package MenaraSolutions\Geographer\Traits
 */
trait HasCollection
{
    /**
     * @param array $params
     * @return mixed
     */
    public function find(array $params = [])
    {
        return $this->getMembers()->find($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function findOne(array $params = [])
    {
        return $this->getMembers()->findOne($params);
    }
}