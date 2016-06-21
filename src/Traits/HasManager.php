<?php

namespace MenaraSolutions\Geographer\Traits;

/**
 * Class HasManager
 * @package MenaraSolutions\FluentGeonames\Traits
 */
trait HasManager
{
    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->manager->setLanguage($language);

        return $this;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function inflict($form)
    {
        $this->manager->setForm($form);

        return $this;
    }

    /**
     * @return $this
     */
    public function useLongNames()
    {
        $this->manager->useLongNames();

        return $this;
    }

    /**
     * @return $this
     */
    public function useShortNames()
    {
        $this->manager->useShortNames();

        return $this;
    }

    /**
     * @return $this
     */
    public function excludePrepositions()
    {
        $this->manager->excludePrepositions();

        return $this;
    }

    /**
     * @return $this
     */
    public function includePrepositions()
    {
        $this->manager->includePrepositions();

        return $this;
    }
}