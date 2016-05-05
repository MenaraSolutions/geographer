<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;

class Planet extends Divisible
{
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