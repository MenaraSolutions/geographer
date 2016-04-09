<?php

namespace MenaraSolutions\FluentGeonames;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country
{
    /**
     * @var \stdClass
     */
    private $config;

    /**
     * Country constructor.
     * @param \stdClass $config
     */
    public function __construct(\stdClass $config)
    {
        $this->config = $config;
    }

    /**
     * Get alpha2 (default) or alpha3 ISO code
     *
     * @param bool $alpha3
     * @return string
     */
    public function getCode($alpha3 = false)
    {
        return $alpha3 ? $this->config->iso_3611[1] : $this->config->iso_3611[0];
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->config->names->short ?: $this->config->names->long;
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->config->names->long ?: $this->config->names->short;
    }
}