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
    protected $parentClass = State::class;

    /**
     * @return string
     */
    protected function getStoragePath()
    {
	return '';
    }

    /**
     * Unique code
     *
     * @return int
     */
    public function getCode()
    {
	return $this->meta['geoid'];
    }

    /**
     * @return int
     */
    public function getGeonamesCode()
    {
	return $this->getCode();
    }
}
