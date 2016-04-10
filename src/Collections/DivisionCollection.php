<?php

namespace MenaraSolutions\FluentGeonames\Collections;

class DivisionCollection implements \ArrayAccess, \Countable
{
    /**
     * @var string $language Current language for output
     */
    private $language;

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
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param $division
     */
    public function addDivision($division)
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