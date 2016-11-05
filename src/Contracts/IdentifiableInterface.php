<?php

namespace MenaraSolutions\Geographer\Contracts;

/**
 * Interface IdentifiableInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface IdentifiableInterface
{
    /**
     * @return bool
     */
    public function expectsLongNames();

    /**
     * @return array
     */
    public function getMeta();

    /**
     * Get an array of unique identification codes for this object
     *
     * @return array
     */
    public function getCodes();
}