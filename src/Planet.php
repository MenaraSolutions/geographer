<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

class Planet extends Divisible
{
    use HasTranslations;

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
        return $this->config->getStoragePath() . 'countries.json';
    }
}