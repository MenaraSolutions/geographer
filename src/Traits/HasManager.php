<?php

namespace MenaraSolutions\Geographer\Traits;

use MenaraSolutions\Geographer\Contracts\ManagerInterface;

/**
 * Class HasManager
 * @package MenaraSolutions\FluentGeonames\Traits
 */
trait HasManager
{
    /**
     * @return ManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param ManagerInterface $manager
     * @return $this
     */
    public function setManager(ManagerInterface $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->manager->setLocale($locale);

        return $this;
    }

    /**
     * @param string $standard
     * @return $this
     */
    public function setStandard($standard)
    {
        $this->manager->setStandard($standard);
        $this->members = null;

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