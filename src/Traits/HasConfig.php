<?php

namespace MenaraSolutions\FluentGeonames\Traits;

use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;

/**
 * Class HasConfig
 * @package MenaraSolutions\FluentGeonames\Traits
 */
trait HasConfig
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->config->setLanguage($language);
    }

    /**
     * @return $this
     */
    public function useLongNames()
    {
        $this->config->useLongNames();

        return $this;
    }

    /**
     * @return $this
     */
    public function useShortNames()
    {
        $this->config->useShortNames();

        return $this;
    }
}