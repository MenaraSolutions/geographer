<?php

namespace MenaraSolutions\FluentGeonames\Collections;

use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

/**
 * Class MemberCollection
 * @package MenaraSolutions\FluentGeonames\Collections
 */
class MemberCollection implements \ArrayAccess, \Countable
{
    use HasTranslations;

    /**
     * @var array $divisions
     */
    private $divisions;

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->divisions as $division) {
            $array[] = $division->toArray();
        }

        return $array;
    }
    
    /**
     * @param $division
     */
    public function add($division)
    {
        $this->divisions[] = $division;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->divisions);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->divisions[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        $this->divisions[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetUnset($offset)
    {
        unset($this->divisions[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->divisions);
    }
}