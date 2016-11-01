<?php

namespace MenaraSolutions\Geographer\Services;

use MenaraSolutions\Geographer\Contracts\ManagerInterface;
use MenaraSolutions\Geographer\Contracts\RepositoryInterface;
use MenaraSolutions\Geographer\Contracts\TranslationAgencyInterface;
use MenaraSolutions\Geographer\Repositories\File;

/**
 * Class DefaultManager
 * @package MenaraSolutions\FluentGeonames\Services
 */
class DefaultManager implements ManagerInterface
{
    /**
     * Supported subdivision standards
     */
    const STANDARD_ISO = 'iso';
    const STANDARD_FIPS = 'fips';
    const STANDARD_GEONAMES = 'geonames';

    /**
     * @var TranslationAgencyInterface $translator
     */
    protected $translator;

    /**
     * @var RepositoryInterface $repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * @var string
     */
    protected $form;

    /**
     * @var string
     */
    protected $standard = self::STANDARD_ISO;

    /**
     * @var bool
     */
    protected $brief = true;

    /**
     * @var bool
     */
    protected $prepositions = true;

    /**
     * @var string
     */
    protected $path;

    /**
     * DefaultConfig constructor.
     * @param string $path
     * @param TranslationAgencyInterface $translator
     * @param RepositoryInterface $repository
     */
    public function __construct($path = null, TranslationAgencyInterface $translator = null, RepositoryInterface $repository= null)
    {
        $this->path = $path ?: self::getDefaultPrefix();
        $this->repository = $repository ?: new File();
        $this->translator = $translator ?: new TranslationAgency($this->path, $this->repository);
    }

    /**
     * @return string
     */
    public static function getDefaultPrefix()
    {
        return dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getStoragePath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setStoragePath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return TranslationAgencyInterface
     */
    public function getTranslator()
    {
        $this->prepositions ? $this->translator->includePrepositions() : $this->translator->excludePrepositions();

        return $this->translator;
    }

    /**
     * @param TranslationAgencyInterface $translator
     * @return $this
     */
    public function setTranslator(TranslationAgencyInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->translator->setRepository($repository);

        return $this;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->language = strtolower(substr($locale, 0, 2));

        return $this;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->translator->setForm($form);

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->language;
    }

    /**
     * @return $this
     */
    public function useLongNames()
    {
        $this->brief = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function useShortNames()
    {
        $this->brief = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function includePrepositions()
    {
        $this->prepositions = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function excludePrepositions()
    {
        $this->prepositions = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function expectsLongNames()
    {
        return ! $this->brief;
    }

    /**
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param string $standard
     * @return $this
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;

        return $this;
    }
}
