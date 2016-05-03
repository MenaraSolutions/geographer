<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

/**
 * Class State
 * @package MenaraSolutions\FluentGeonames
 */
class State extends Divisible
{
    use HasTranslations;

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
    protected function getStoragePath()
    {
        return $this->config->getStoragePath() . $this->getCode() . '.json';
    }
}