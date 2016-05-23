<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

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
     * @return \stdClass
     */
    public function getMeta();
}