<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Traits\HasTranslations;
use MenaraSolutions\FluentGeonames\Collections\MemberCollection;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country extends Divisible
{
    use HasTranslations;
    
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
        return $this->meta->iso_3611[0];
    }

    /**
     * Get alpha3 ISO code
     *
     * @return string
     */
    public function getCode3()
    {
        return $this->meta->iso_3611[1];
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->getText($this->meta->names->short) ?: $this->getText($this->meta->names->long);
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->getText($this->meta->names->long) ?: $this->getText($this->meta->names->short);
    }

    /**
     * @param $input
     * @return string
     */
    public function getText($input)
    {
        return $this->translator->translate($input, get_class($this), $this->language);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'code_3' => $this->getCode3(),
            'short_name' => $this->getShortName(),
            'long_name' => $this->getLongName()
        ];
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
        return "resources/states/" . $this->getCode() . ".json";
    }
}