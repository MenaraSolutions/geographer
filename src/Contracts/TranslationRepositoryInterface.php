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
     * @return string
     */
    public function translate(ConfigInterface $config, IdentifiableInterface $subject);
}