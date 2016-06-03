<?php

namespace MenaraSolutions\Geographer;

/**
 * Class City
 * @package MenaraSolutions\Geographer
 */
class City extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = null;

    /**
     * @var string
     */
    protected static $parentClass = State::class;

    /**
     * Unique code
     *
     * @return int
     */
    public function getCode()
    {
	    return $this->meta['ids']['geonames'];
    }
    
    /**
     * @return int
     */
    public function getGeonamesCode()
    {
	    return $this->getCode();
    }
}
