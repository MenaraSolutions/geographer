<?php

namespace MenaraSolutions\Geographer\Collections;

use MenaraSolutions\Geographer\Collections\Traits\ImplementsArray;
use MenaraSolutions\Geographer\Contracts\ConfigInterface;
use MenaraSolutions\Geographer\Traits\HasConfig;

/**
 * Class MemberCollection
 * @package MenaraSolutions\FluentGeonames\Collections
 */
class MemberCollection extends \ArrayObject
{
    use HasConfig, ImplementsArray;
    
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
        return reset($this->divisions);
    }
    
    /**
     * @param $division
     */
    public function add($division)
    {
        $this->divisions[] = $division;
    }
    
    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static($this->config, array_filter($this->divisions, $callback));
        }

        return new static($this->config, array_filter($this->divisions));
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
