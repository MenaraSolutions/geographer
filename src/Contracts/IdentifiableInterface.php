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
}