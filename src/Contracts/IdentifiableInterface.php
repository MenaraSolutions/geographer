<?php

namespace MenaraSolutions\Geographer\Contracts;

/**
 * Interface IdentifiableInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface IdentifiableInterface
{
    /**
     * Unique code
     * 
     * @return string|int
     */
    public function getCode();

    /**
     * @return bool
     */
    public function expectsLongNames();

    /**
     * @return array
     */
    public function getMeta();
}