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
     * @return string|bool
     */
    public function getFipsCode()
    {
        return isset($this->meta['ids']['fips']) ? $this->meta['ids']['fips'] : false;
    }

    /**
     * @return string|bool
     */
    public function getIsoCode()
    {
        return isset($this->meta['ids']['iso_3166']) ? $this->meta['ids']['iso_3166'] : false;
    }

    /**
     * @return Collections\MemberCollection
     */
    public function getCities()
    {
        return $this->getMembers();
    }
}
