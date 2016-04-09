<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

/**
 * Class DefaultConfig
 * @package MenaraSolutions\FluentGeonames\Services
 */
class DefaultConfig implements ConfigInterface
{
    /**
     * @return string
     */
    public function getStoragePath()
    {
        return dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
    }
}