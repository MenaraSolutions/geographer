<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface ConfigInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface ConfigInterface
{
    /**
     * @return string
     */
    public function getStoragePath();
}