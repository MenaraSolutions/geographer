<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface IdentifiableInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface IdentifiableInterface
{
    /**
     * @return string|int
     */
    public function getCode();
}