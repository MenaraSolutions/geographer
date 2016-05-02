<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

class Planet extends Divisible
{
    use HasTranslations;

    /**
     * @var ConfigInterface $config
     */
    private $config;

    /**
     * @var string
     */
    protected $memberClass = Country::class;

    /**
     * @return MemberCollection
     */
    public function getCountries()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    protected function getStoragePath() {
        return "resources/countries.json";
    }
}