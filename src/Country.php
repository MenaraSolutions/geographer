<?php

namespace MenaraSolutions\Geographer;

use MenaraSolutions\Geographer\Collections\MemberCollection;

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
     * @var string
     */
    protected static $parentClass = Earth::class;

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
     * @return string
     */
    public function getParentCode()
    {
        return 'SOL-III';
    }

    /**
     *
     */
    public function getGeonamesCode()
    {
        return $this->meta['ids']['geonames'];
    }

    /**
     * @return int
     */
    public function getArea()
    {
        return isset($this->meta['area']) ? $this->meta['area'] : 0;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return isset($this->meta['currency']) ? $this->meta['currency'] : false;
    }

    /**
     * @return int|bool
     */
    public function getPhonePrefix()
    {
        return isset($this->meta['phone']) ? $this->meta['phone'] : false;
    }

    /**
     * @return int
     */
    public function getPopulation()
    {
        return isset($this->meta['population']) ? $this->meta['population'] : 0;
    }

    /**
     * @return string
     */
    public function getContinent()
    {
        return $this->meta['continent'];
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->meta['languages'][0];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'code_3' => $this->getCode3(),
            'name' => $this->getName(),
            'geonames_id' => $this->getGeonamesCode(),
            'area' => $this->getArea(),
            'phone_prefix' => $this->getPhonePrefix(),
            'currency_code' => $this->getCurrencyCode(),
            'population' => $this->getPopulation(),
            'continent' => $this->getContinent(),
            'language' => $this->getLanguage()
        ];
    }

    /**
     * @return bool|Divisible
     */
    public function getCapital()
    {
        return $this->findOne([
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
}