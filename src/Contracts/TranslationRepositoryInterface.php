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
     * @param string $context
     * @param string $language
     * @return string
     */
    public function translate($input, $context, $language);
}