<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

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
     * Country constructor.
     * @param \stdClass $meta
     * @param ConfigInterface $config
     */
    public function __construct(\stdClass $meta, ConfigInterface $config)
    {
        parent::__construct($meta, $config);
    }

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
        return $this->translate($this->meta->names->short) ?: $this->translate($this->meta->names->long);
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->translate($this->meta->names->long) ?: $this->translate($this->meta->names->short);
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