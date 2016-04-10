<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\DivisionCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\Services\DefaultConfig;
use MenaraSolutions\FluentGeonames\Traits\HasPublicFields;

class Planet
{
    use HasPublicFields;

    /**
     * @var DivisionCollection $countries
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
     * @param DivisionCollection $collection
     * @return void
     * @throws MisconfigurationException
     */
    private function loadDefaultCountries(DivisionCollection $collection = null)
    {
        $countriesFile = $this->config->getStoragePath() . "countries.json";
        if (!file_exists($countriesFile)) throw new MisconfigurationException('Unable to load countries');

        $collection = $collection ?: (new DivisionCollection());

        foreach(json_decode(file_get_contents($countriesFile)) as $countryConfig) {
            $collection->addDivision(new Country($countryConfig));
        }

        $this->setCountries($collection);
    }
}