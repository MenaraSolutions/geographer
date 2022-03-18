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
    public function getIterator(): \Iterator {
        return new \ArrayIterator($this->divisions);
    }
    
    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->divisions);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->divisions[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet($offset, $value): void
    {
        $this->divisions[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->divisions[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->divisions);
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->divisions);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->divisions = unserialize($serialized);
    }
}
