<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;

/**
 * Class TranslationRepository
 * @package MenaraSolutions\FluentGeonames\Services
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @param string $input
     * @param string $context
     * @param string $language
     * @return string
     */
    public function translate($input, $context, $language)
    {
        // English is the source language
        if ($language == 'en') return $input;

        return 'DDsadsd';
    }
}