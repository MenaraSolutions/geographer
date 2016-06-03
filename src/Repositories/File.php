<?php

namespace MenaraSolutions\Geographer\Repositories;

use MenaraSolutions\Geographer\Contracts\RepositoryInterface;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;
use MenaraSolutions\Geographer\City;

class File implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array $paths
     */
    protected static $paths = [
        Earth::class => 'countries.json',
        Country::class => 'states' . DIRECTORY_SEPARATOR . 'code.json',
        State::class => 'cities' . DIRECTORY_SEPARATOR . 'parentCode.json'
    ];

    /**
     * File constructor.
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $class
     * @param string $prefix
     * @param array $params
     * @return string
     * @throws MisconfigurationException
     */
    public static function getPath($class, $prefix, array $params)
    {
        if (! isset(self::$paths[$class])) throw new MisconfigurationException($class . ' is not supposed to load data');

        return str_replace(array_keys($params), array_values($params), $prefix . self::$paths[$class]);
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param $class
     * @param array $params
     * @return array
     */
    public function getData($class, array $params)
    {
        $file = self::getPath($class, $this->prefix, $params);

        try {
            $data = self::loadJson($file);
        } catch (FileNotFoundException $e) {
            // Some divisions don't have data files, so we don't want to throw the exception
            return [];
        }

        if ($class == State::class && isset($params['code'])) {
            foreach ($data as $key => $meta) {
                if ($meta['parent'] != $params['code']) unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param int $geonamesId
     * @param string $class
     * @param string $prefix
     * @return array
     * @throws ObjectNotFoundException
     */
    public static function indexSearch($geonamesId, $class, $prefix)
    {
        $index = static::loadJson($prefix . 'indexCity.json');
        if (! isset($index[$geonamesId])) throw new ObjectNotFoundException('Cannot find object with id ' . $geonamesId);

        $parentCode = $index[$geonamesId];
        $path = self::getPath($class, $prefix, compact('parentCode'));
        $members = static::loadJson($path);

        foreach ($members as $member) {
            if ($member['ids']['geonames'] == $geonamesId) return $member;
        }

        throw new ObjectNotFoundException('Cannot find meta for city #' . $geonamesId);
    }

    /**
     * @param string $path
     * @return array
     * @throws ObjectNotFoundException
     * @throws FileNotFoundException
     */
    protected static function loadJson($path)
    {
        if (! file_exists($path)) throw new FileNotFoundException('File not found: ' . $path);
        return json_decode(file_get_contents($path), true);
    }
}