<?php

namespace MenaraSolutions\FluentGeonames;

/**
 * Class State
 * @package MenaraSolutions\FluentGeonames
 */
class State extends Divisible
{
    /**
     * Get Geonames code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->meta->ids->geonames;
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
     * @return string
     */
    protected function getStoragePath()
    {
        return $this->config->getStoragePath() . $this->getCode() . '.json';
    }
}