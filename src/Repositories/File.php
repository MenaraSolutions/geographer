<?php

namespace MenaraSolutions\Geographer\Repositories;

use MenaraSolutions\Geographer\Contracts\RepositoryInterface;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\State;

class File implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array $paths
     */
    protected $paths = [
        Earth::class => 'countries.json',
        Country::class => 'states' . DIRECTORY_SEPARATOR . 'code.json',
        State::class => 'cities' . DIRECTORY_SEPARATOR . 'parentCode.json'
    ];

    /**
     * File constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->prefix = $path;
    }

    /**
     * @param string $class
     * @param array $params
     * @return string
     * @throws MisconfigurationException
     */
    public function getPath($class, array $params)
    {
        if (! isset($this->paths[$class])) throw new MisconfigurationException('This class is not supposed to load data');

        return str_replace(array_keys($params), array_values($params), $this->prefix . $this->paths[$class]);
    }

    /**
     * @param $class
     * @param array $params
     * @return array
     */
    public function getData($class, array $params)
    {
        $file = $this->getPath($class, $params);
        if (! file_exists($file)) return [];

        $data = json_decode(file_get_contents($file), true);

        if ($class == State::class && isset($params['code'])) {
            foreach ($data as $key => $meta) {
                if ($meta['parent'] != $params['code']) unset($data[$key]);
            }
        }

        return $data;
    }
}