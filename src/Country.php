<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Traits\HasTranslations;
use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

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
     * Country constructor.
     * @param \stdClass $meta
     * @param ConfigInterface $config
     */
    public function __construct(\stdClass $meta, ConfigInterface $config)
    {
        parent::__construct($meta, $config);

        $this->config->setStoragePath($this->config->getStoragePath() . DIRECTORY_SEPARATOR . 'states' . DIRECTORY_SEPARATOR);
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
        return $this->config->getStoragePath() . $this->getCode() . '.json';
    }
}