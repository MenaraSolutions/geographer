<?php

namespace MenaraSolutions\FluentGeonames\Contracts;

/**
 * Interface ConfigInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface ConfigInterface
{
    /**
     * @return string
     */
    public function getStoragePath();

    /**
     * @param string $path
     * @return ConfigInterface
     */
    public function setStoragePath($path);
    
    /**
     * @return TranslationRepositoryInterface
     */
    public function getTranslator();

    /**
     * @param TranslationRepositoryInterface $translator
     * @return ConfigInterface
     */
    public function setTranslator(TranslationRepositoryInterface $translator);
}