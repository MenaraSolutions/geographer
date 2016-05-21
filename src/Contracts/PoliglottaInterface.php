<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface PoliglottaInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface PoliglottaInterface
{
    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateCountry(IdentifiableInterface $subject);

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateState(IdentifiableInterface $subject);

    /**
     * @param IdentifiableInterface $subject
     * @return string
     */
    public function translateCity(IdentifiableInterface $subject);
}