<?php

namespace MenaraSolutions\Geographer\Contracts;

/**
 * Interface ManagerInterface
 * @package MenaraSolutions\FluentGeonames\Contracts
 */
interface ManagerInterface
{
    /**
     * @return string
     */
    public function getStoragePath();

    /**
     * @param string $path
     * @return ManagerInterface
     */
    public function setStoragePath($path);
    
    /**
     * @return TranslationAgencyInterface
     */
    public function getTranslator();

    /**
     * @param TranslationAgencyInterface $translator
     * @return ManagerInterface
     */
    public function setTranslator(TranslationAgencyInterface $translator);

    /**
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @param RepositoryInterface $repository
     * @return ManagerInterface
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * @param string $form
     */
    public function setForm($form);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $language
     * @return ManagerInterface
     */
    public function setLocale($language);

    /**
     * @return ManagerInterface
     */
    public function useShortNames();

    /**
     * @return ManagerInterface
     */
    public function useLongNames();

    /**
     * @return ManagerInterface
     */
    public function includePrepositions();

    /**
     * @return ManagerInterface
     */
    public function excludePrepositions();

    /**
     * @return bool
     */
    public function expectsLongNames();

    /**
     * @param string $standard
     * @return $this
     */
    public function setStandard($standard);

    /**
     * @return string
     */
    public function getStandard();
}