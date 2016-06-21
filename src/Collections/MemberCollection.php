<?php

namespace MenaraSolutions\Geographer\Collections;

use MenaraSolutions\Geographer\Collections\Traits\ImplementsArray;
use MenaraSolutions\Geographer\Contracts\ManagerInterface;
use MenaraSolutions\Geographer\Traits\HasManager;

/**
 * Class MemberCollection
 * @package MenaraSolutions\FluentGeonames\Collections
 */
class MemberCollection extends \ArrayObject
{
    use HasManager, ImplementsArray;
    
    /**
     * @var array $divisions
     */
    private $divisions = [];

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * MemberCollection constructor.
     * @param ManagerInterface $config
     * @param array $divisions
     */
    public function __construct(ManagerInterface $config, $divisions = [])
    {
        parent::__construct();

        $this->manager = $config;
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
            return new static($this->manager, array_filter($this->divisions, $callback));
        }

        return new static($this->manager, array_filter($this->divisions));
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

        return new static($this->manager, $results);
    }
}
