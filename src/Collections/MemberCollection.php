<?php

namespace MenaraSolutions\Geographer\Collections;

use MenaraSolutions\Geographer\Contracts\ConfigInterface;
use MenaraSolutions\Geographer\Traits\HasConfig;

/**
 * Class MemberCollection
 * @package MenaraSolutions\FluentGeonames\Collections
 */
class MemberCollection extends \ArrayObject
{
    use HasConfig;
    
    /**
     * @var array $divisions
     */
    private $divisions = [];

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * MemberCollection constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config, $divisions = [])
    {
        parent::__construct();

        $this->config = $config;
	$this->divisions = $divisions;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->divisions);
    }

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
     * @return mixed
     */
    public function first()
    {
        return $this->divisions[0];   
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

    /**
     * Sort the collection
     *
     * @param  string $field
     * @param  int   $options
     * @param  bool  $descending
     * @return static
     */
    public function sortBy($field, $options = SORT_REGULAR, $descending = false)
    {
        $results = [];

        foreach ($this->divisions as $key => $value) {
	    $meta = $value->toArray();
            $results[$key] = $meta[$field];
        }

        $descending ? arsort($results, $options)
                    : asort($results, $options);
        
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->divisions[$key];
        }

        return new static($this->config, $results);
    }
}
