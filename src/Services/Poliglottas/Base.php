<?php

namespace MenaraSolutions\Geographer\Services\Poliglottas;

use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Exceptions\MisconfigurationException;
use MenaraSolutions\Geographer\Exceptions\FileNotFoundException;
use MenaraSolutions\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Base
 * @package App\Services\Poliglottas
 */
abstract class Base
{
    /**
     * @var string
     */
    protected $base_path;

    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var array
     */
    protected $prefixes = [
        Country::class => 'country',
        State::class => 'state',
        Earth::class => 'planet'
    ];

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->base_path = $basePath;
    }

    /**
     * @param $class
     * @throws MisconfigurationException
     * @return string
     */
    protected function getPrefix($class)
    {
        if (! array_key_exists($class, $this->prefixes)) throw new MisconfigurationException('Unsupported class');

        return $this->prefixes[$class];
    }

    /**
     * @param string $class
     * @param string|int $id
     * @return string|bool
     */
    public function getStoragePath($class, $id)
    {
        switch ($class) {
            case Country::class:
                return $this->base_path . 'translations/' . $this->getPrefix($class) . DIRECTORY_SEPARATOR . $this->code . '.json';

                break;

            case State::class;
                return $this->base_path . 'translations/' . $this->getPrefix($class) . $id . DIRECTORY_SEPARATOR . $this->code . '.json';

                break;

            default:
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @throws FileNotFoundException
     */
    public function loadDictionaries(IdentifiableInterface $subject)
    {
        $source = $this->getStoragePath(get_class($subject), $subject->getCode());

        if (!file_exists($source)) throw new FileNotFoundException('File not found: ' . $source);
        if (isset($this->cache[$this->getPrefix(get_class($subject))][$this->code])) return;

        foreach (json_decode(file_get_contents($source), true) as $one) {
            $this->cache[$this->getPrefix(get_class($subject))][$this->code][$one['code']] = $one;
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @return array
     */
    protected function fromCache(IdentifiableInterface $subject)
    {
        return $this->cache[$this->getPrefix(get_class($subject))][$this->code][$subject->getCode()];
    }
}