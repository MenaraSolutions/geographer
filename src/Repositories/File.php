<?php

namespace MenaraSolutions\Geographer\Repositories;

use MenaraSolutions\Geographer\Contracts\RepositoryInterface;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;

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
     * @var array
     */
    protected static $indexes = [
        State::class => 'indexState.json'
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

        // ToDo: remove this logic from here
        if ($class == State::class && isset($params['code'])) {
            foreach ($data as $key => $meta) {
                if ($meta['parent'] != $params['code']) unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param int $id
     * @param string $class
     * @param string $prefix
     * @return array
     * @throws ObjectNotFoundException
     */
    public static function indexSearch($id, $class, $prefix)
    {
        $index = static::loadJson($prefix . self::$indexes[$class]);
        if (! isset($index[$id])) throw new ObjectNotFoundException('Cannot find object with id ' . $id);

        $parentCode = $index[$id];
        $path = self::getPath($class, $prefix, compact('parentCode'));
        $members = static::loadJson($path);

        foreach ($members as $member) {
            if ($member['ids']['geonames'] == $id) return $member;
        }

        throw new ObjectNotFoundException('Cannot find meta for division #' . $id);
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