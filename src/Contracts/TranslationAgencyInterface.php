<?php

namespace MenaraSolutions\Geographer\Contracts;

/**
 * Interface TranslationRepositoryInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface TranslationAgencyInterface
{
    /**
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $language);

    /**
     * @return array
     */
    public function getSupportedLanguages();

    /**
     * @param $form
     * @return TranslationAgencyInterface
     */
    public function setForm($form);

    /**
     * @return TranslationAgencyInterface
     */
    public function includePrepositions();

    /**
     * @return TranslationAgencyInterface
     */
    public function excludePrepositions();
}