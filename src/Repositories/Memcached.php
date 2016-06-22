<?php

namespace MenaraSolutions\Geographer\Repositories;

use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;
use MenaraSolutions\Geographer\Contracts\RepositoryInterface;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Exceptions\ObjectNotFoundException;
use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\Repositories\File;

class Memcached implements RepositoryInterface
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
        Country::class => 'indexCountry.json',
        State::class => 'indexState.json'
    ];

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var Memcached
     */
    protected $client;

    /**
     * Memcached constructor.
     * @param string $prefix
     * @param Memcached $client
     * @throws MisconfigurationException
     */
    public function __construct($prefix, $client = null)
    {
        $this->prefix = $prefix;

        if (! class_exists(\Memcached::class)) {
            throw new MisconfigurationException('Memcached repository requires Memcached module');
        }

        if (! $client) {
            $client = new \Memcached();
            $client->addServer('127.0.0.1', 11211);
        }

        $this->client = $client;
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
     * @param string $class
     * @param array $params
     * @return array
     */
    public function getData($class, array $params)
    {
        $path = self::getPath($class, $this->prefix, $params);
        $data = $this->client->get($path);

        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) {
            $data = $this->getDataFromFile($class, $params);
        }

        return $data;
    }

    /**
     * @param $class
     * @param array $params
     * @return array
     */
    public function getDataFromFile($class, array $params)
    {
        $file = self::getPath($class, $this->prefix, $params);

        try {
            $data = File::loadJson($file);
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
     * @param $path
     * @param $id
     * @return mixed
     * @throws ObjectNotFoundException
     */
    protected function getCodeFromIndex($path, $id)
    {
        $index = $this->client->get($path);

        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) {
            $index = File::loadJson($path);
            $this->client->set($path, $index, 0);
        }

        if (! isset($index[$id])) throw new ObjectNotFoundException('Cannot find object with id ' . $id);

        return $index[$id];
    }

    /**
     * @param int $id
     * @param string $class
     * @return array
     * @throws ObjectNotFoundException
     */
    public function indexSearch($id, $class)
    {
        $code = $this->getCodeFromIndex($this->prefix . self::$indexes[$class], $id);

        $key = ($class == State::class) ? 'parentCode' : 'code';
        $path = self::getPath($class, $this->prefix, [ $key => $code ]);

        $cache = $this->client->get($path);

        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) {
            $cache = File::loadJson($path);
            $this->client->set($path, $cache, 0);
        }

        foreach ($cache as $member) {
            if ($member['ids']['geonames'] == $id) return $member;
        }

        throw new ObjectNotFoundException('Cannot find meta for division #' . $id);
    }

    /**
     * @param IdentifiableInterface $subject
     * @param $language
     * @return array
     */
    public function getTranslations(IdentifiableInterface $subject, $language)
    {
        $elements = explode('\\', get_class($subject));
        $key = strtolower(end($elements));

        if (get_class($subject) == City::class) {
            $country = $subject->getMeta()['country'];
            $path = $this->prefix . 'translations/' . $key . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $country .  '.json';
        } else {
            $path = $this->prefix . 'translations/' . $key . DIRECTORY_SEPARATOR . $language . '.json';
        }

        $this->client->get($path);
        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) $this->loadTranslations($path);

        $translation = $this->client->get($path . $subject->getCode()) ?: null;

        return $translation;
    }

    /**
     * @param string $path
     * @throws FileNotFoundException
     */
    protected function loadTranslations($path)
    {
        $meta = File::loadJson($path);

        $this->client->set($path, true, 0);

        foreach ($meta as $one) {
            $this->client->set($path . $one['code'], $one, 0);
        }
    }
}
