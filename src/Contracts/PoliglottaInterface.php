<?php

namespace MenaraSolutions\Geographer\Contracts;

/**
 * Interface PoliglottaInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface PoliglottaInterface
{
    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form);
}