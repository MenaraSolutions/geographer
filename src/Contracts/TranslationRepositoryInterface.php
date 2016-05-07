<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface TranslationRepositoryInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface TranslationRepositoryInterface
{
    /**
     * @param string $input
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate($input, IdentifiableInterface $subject, $language);
}