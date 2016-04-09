<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
use MenaraSolutions\FluentGeonames\Traits\HasPublicFields;

class Planet
{
    use HasPublicFields;

    /**
     * @var array $countries
     */
    private $countries;

    /**
     * Class properties visible to the world
     * @var array
     */
    private $public = [
        'countries'
    ];

    /**
     * @var ConfigInterface $config
     */
    private $config;

    /**
     * Planet constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        $this->config = $config ?: new DefaultConfig();

        $this->loadDefaultCountries();
    }

    /**
     * @return void
     * @throws MisconfigurationException
     */
    private function loadDefaultCountries()
    {
        $countriesFile = $this->config->getStoragePath() . "countries.json";
        if (!file_exists($countriesFile)) throw new MisconfigurationException('Unable to load countries');

        $countries = [];

        foreach(json_decode(file_get_contents($countriesFile)) as $countryConfig) {
            $countries[] = new Country($countryConfig);
        }

        $this->setCountries($countries);
    }
}