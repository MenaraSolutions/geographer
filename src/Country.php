<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Contracts\ConfigInterface;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = State::class;

    /**
     * Get alpha2 ISO code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->meta['ids']['iso_3611'][0];
    }

    /**
     * Get alpha3 ISO code
     *
     * @return string
     */
    public function getCode3()
    {
        return $this->meta['ids']['iso_3611'][1];
    }

    /**
     *
     */
    public function getGeonamesCode()
    {
        return $this->meta['ids']['geonames'];
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'code_3' => $this->getCode3(),
            'name' => $this->getName()
        ];
    }

    /**
     * @return bool|Divisible
     */
    public function getCapital()
    {
        return $this->find([
            'capital' => true
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getStates()
    {
        return $this->getMembers();
    }

    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return $this->config->getStoragePath() . 'states' . DIRECTORY_SEPARATOR . $this->getCode() . '.json';
    }
}