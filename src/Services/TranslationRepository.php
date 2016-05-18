<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\IdentifiableInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\State;
use MenaraSolutions\FluentGeonames\Earth;
use MenaraSolutions\FluentGeonames\Exceptions\FileNotFoundException;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

/**
 * Class TranslationRepository
 * @package MenaraSolutions\FluentGeonames\Services
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @var string
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * @var string
     */
    protected $base_path;

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
     * TranslationRepository constructor.
     * @param string $base_path
     */
    public function __construct($base_path)
    {
        $this->base_path = $base_path;
    }

    /**
     * @param ConfigInterface $config
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate(ConfigInterface $config, IdentifiableInterface $subject, $language)
    {
        // English is the source language
        if ($language == self::DEFAULT_LANGUAGE) return null;

        $this->loadTranslations($subject, $language);

        if (get_class($subject) == Country::class) return $this->translateCountry($config, $subject, $language);
    }

    /**
     * @param ConfigInterface $config
     * @param IdentifiableInterface $subject
     * @param $language
     * @return string
     */
    public function translateCountry(ConfigInterface $config, IdentifiableInterface $subject, $language)
    {
        $field = $config->expectsLongNames() ? 'long' : 'short';
        $backupField = !$config->expectsLongNames() ? 'long' : 'short';

        foreach ($this->cache[$this->getPrefix(get_class($subject))][$language] as $country) {
            if ($country['code'] == $subject->getCode()) {
                return $country[$field]['default'] ?: $country[$backupField]['default'];
            }
        }
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

            default;
        }
    }

    /**
     * @param IdentifiableInterface $subject
     * @param $language
     * @throws FileNotFoundException
     */
    public function loadTranslations(IdentifiableInterface $subject, $language)
    {
        $source = $this->getStoragePath(get_class($subject), $subject->getCode(), $language);

        if (!file_exists($source)) throw new FileNotFoundException('File not found: ' . $source);

        if (isset($this->cache[$this->getPrefix(get_class($subject))][$language])) return;

        $this->cache[$this->getPrefix(get_class($subject))][$language] = json_decode(file_get_contents($source), true);
    }
}