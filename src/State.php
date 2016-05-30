<?php

namespace MenaraSolutions\Geographer;

/**
 * Class State
 * @package MenaraSolutions\FluentGeonames
 */
class State extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = City::class;

    /**
     * @var string
     */
    protected $parentClass = Country::class;

    /**
     * Get Geonames code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->meta['ids']['geonames'];
    }

    /**
     * @return Collections\MemberCollection
     */
    public function getCities()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return $this->config->getStoragePath() . $this->getCode() . 'cities.json';
    }
}