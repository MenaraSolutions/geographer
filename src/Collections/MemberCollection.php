<?php

namespace MenaraSolutions\Geographer\Collections;

use MenaraSolutions\Geographer\Collections\Traits\ImplementsArray;
use MenaraSolutions\Geographer\Contracts\ManagerInterface;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;
use MenaraSolutions\Geographer\Traits\HasManager;
use MenaraSolutions\Geographer\Divisible;

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
     * @param string $key
     * @return array
     */
    public function pluck($key)
    {
        return array_map(function($division) use ($key) {
            return isset($division[$key]) ? $division[$key] : null;
        }, $this->toArray());
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->divisions);
    }

    /**
     * @param $key
     * @return mixed
     * @throws ObjectNotFoundException
     */
    public function get($key)
    {
        if (! isset($this->divisions[$key])) throw new ObjectNotFoundException('Unknown code');

        return $this->divisions[$key];
    }

    /**
     * @param $division
     * @param string|int $key
     * @return $this
     */
    public function add($division, $key)
    {
        $this->divisions[$key] = $division;

        return $this;
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
     * @param Divisible $member
     * @param array $params
     * @return bool
     */
    private function match(Divisible $member, array $params)
    {
        $memberArray = $member->toArray();
        $match = true;

        foreach ($params as $key => $value) {
            if (!isset($memberArray[$key]) || strcasecmp($memberArray[$key], $value) != 0) $match = false;
        }

        return $match;
    }

    /**
     * @param array $params
     * @return MemberCollection
     */
    public function find(array $params = [])
    {
        $members = new self($this->manager);

        foreach($this->divisions as $key => $member) {
            if ($this->match($member, $params)) {
                $members->add($member, $key);
            }
        }

        return $members;
    }

    /**
     * @param array $params
     * @return Divisible|bool
     */
    public function findOne(array $params = [])
    {
        if (array_keys($params) == ['code']) {
            return $this->get(strtoupper($params['code']));
        }

        return $this->find($params)->first();
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
