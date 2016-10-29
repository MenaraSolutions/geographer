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
    protected static $parentClass = Country::class;

    /**
     * @var string
     */
    protected $standard = 'geonames';

    /**
     * @var array
     */
    protected $exposed = [
        'code' => 'ids.geonames',
        'fipsCode' => 'ids.fips',
        'isoCode' => 'ids.iso_3166_2',
        'geonamesCode' => 'ids.geonames',
        'name'
    ];

    /**
     * @return Collections\MemberCollection
     */
    public function getCities()
    {
        return $this->getMembers();
    }
}
