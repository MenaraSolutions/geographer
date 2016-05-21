<?php

namespace MenaraSolutions\FluentGeonames\Services\Poliglottas;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\PoliglottaInterface;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\State;
use MenaraSolutions\FluentGeonames\Earth;
use MenaraSolutions\FluentGeonames\Exceptions\FileNotFoundException;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;

/**
 * Class Russian
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Russian implements PoliglottaInterface
{
    /**
     * @var string
     */
    protected $base_path;

    /**
     * @var string
     */
    protected $code = 'ru';

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
     * Russian constructor.
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->base_path = $basePath;
    }

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateCountry(IdentifiableInterface $subject)
    {
        $this->loadDictionaries($subject);

        $field = $subject->expectsLongNames() ? 'long' : 'short';
        $backupField = ! $subject->expectsLongNames() ? 'long' : 'short';

        foreach ($this->cache[$this->getPrefix(get_class($subject))][$this->code] as $country) {
            if ($country['code'] == $subject->getCode()) {
                return $country[$field]['default'] ?: $country[$backupField]['default'];
            }
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateState(IdentifiableInterface $subject)
    {
        // TODO: Implement translateState() method.
    }

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateCity(IdentifiableInterface $subject)
    {
        // TODO: Implement translateCity() method.
    }

    /**
     * @param $class
     * @throws MisconfigurationException
     * @return string
     */
    private function getPrefix($class)
    {
        if (! array_key_exists($class, $this->prefixes)) throw new MisconfigurationException('Unsupported class');

        return $this->prefixes[$class];
    }

    /**
     * @param IdentifiableInterface $subject
     * @throws FileNotFoundException
     */
    public function loadDictionaries(IdentifiableInterface $subject)
    {
        $source = $this->getStoragePath(get_class($subject), $subject->getCode(), $this->code);

        if (!file_exists($source)) throw new FileNotFoundException('File not found: ' . $source);

        if (isset($this->cache[$this->getPrefix(get_class($subject))][$this->code])) return;

        $this->cache[$this->getPrefix(get_class($subject))][$this->code] = json_decode(file_get_contents($source), true);
    }

    /**
     * @param string $class
     * @param string|int $id
     * @param $language
     * @return string|bool
     */
    public function getStoragePath($class, $id, $language)
    {
        switch ($class) {
            case Country::class:
                return $this->base_path . 'translations/' . $this->getPrefix($class) . DIRECTORY_SEPARATOR . $language . '.json';

                break;

            case State::class;
                return $this->base_path . 'translations/' . $this->getPrefix($class) . $id . DIRECTORY_SEPARATOR . $language . '.json';

                break;

            default:
        }
    }
}