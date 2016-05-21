<?php

namespace MenaraSolutions\FluentGeonames\Traits;

/**
 * Class HasConfig
 * @package MenaraSolutions\FluentGeonames\Traits
 */
trait HasConfig
{
    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->config->setLanguage($language);

        return $this;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function inflict($form)
    {
        $this->config->setForm($form);

        return $this;
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