<?php

namespace MenaraSolutions\Geographer\Contracts;

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
     * @return TranslationAgencyInterface
     */
    public function getTranslator();

    /**
     * @param TranslationAgencyInterface $translator
     * @return ConfigInterface
     */
    public function setTranslator(TranslationAgencyInterface $translator);

    /**
     * @param string $form
     */
    public function setForm($form);

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @param string $language
     * @return ConfigInterface
     */
    public function setLanguage($language);

    /**
     * @return ConfigInterface
     */
    public function useShortNames();

    /**
     * @return ConfigInterface
     */
    public function useLongNames();

    /**
     * @return ConfigInterface
     */
    public function includePrepositions();

    /**
     * @return ConfigInterface
     */
    public function excludePrepositions();

    /**
     * @return bool
     */
    public function expectsLongNames();
}