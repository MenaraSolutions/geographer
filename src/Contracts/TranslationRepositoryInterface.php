<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface TranslationRepositoryInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface TranslationRepositoryInterface
{
    /**
     * @param ConfigInterface $config
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate(ConfigInterface $config, IdentifiableInterface $subject, $language);
}