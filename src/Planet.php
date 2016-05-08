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
    public function getShortName()
    {
        return 'Earth';
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return 'The Blue Marble';
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return 'SOL-III';
    }
    
    /**
     * @return string
     */
    protected function getStoragePath() {
        return $this->config->getStoragePath() . 'countries.json';
    }
}