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

class Memcached extends File implements RepositoryInterface
{
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
    public function __construct($prefix = null, $client = null)
    {
        parent::__construct($prefix);

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
     * @param array $params
     * @return array
     */
    public function getData($class, array $params)
    {
        $path = $this->getPath($class, $this->prefix, $params);
        $data = $this->client->get($path);

        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) {
            $data = parent::getData($class, $params);
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
            $index = $this->loadJson($path);
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
        $code = $this->getCodeFromIndex($this->prefix . DIRECTORY_SEPARATOR . self::$indexes[$class], $id);

        $key = ($class == State::class) ? 'parentCode' : 'code';
        $path = $this->getPath($class, $this->prefix, [ $key => $code ]);

        $cache = $this->client->get($path);

        if ($this->client->getResultCode() == \Memcached::RES_NOTFOUND) {
            $cache = $this->loadJson($path);
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
        $path = $this->getTranslationsPath($subject, $language);

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
        $meta = $this->loadJson($path);

        $this->client->set($path, true, 0);

        foreach ($meta as $one) {
            $this->client->set($path . $one['code'], $one, 0);
        }
    }
}
