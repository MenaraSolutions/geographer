<?php

namespace MenaraSolutions\Geographer\Collections\Traits;

/**
 * Class ImplementsArray
 * @package MenaraSolutions\Geographer\Collections\Traits
 */
trait ImplementsArray
{
    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->divisions);
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

    /**
     * @return mixed
     */
    public function serialize()
    {
        return serialize($this->divisions);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->divisions = unserialize($serialized);
    }
}
