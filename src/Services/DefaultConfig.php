<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;

/**
 * Class DefaultConfig
 * @package MenaraSolutions\FluentGeonames\Services
 */
class DefaultConfig implements ConfigInterface
{
    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var TranslationRepositoryInterface $translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * @var bool
     */
    protected $brief = false;

    /**
     * DefaultConfig constructor.
     * @param string $path
     * @param TranslationRepositoryInterface $translator
     */
    public function __construct($path = null, TranslationRepositoryInterface $translator = null)
    {
        $this->path = $path ?: dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        $this->translator = $translator ?: new TranslationRepository($this->path);
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
     * @return TranslationRepositoryInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param TranslationRepositoryInterface $translator
     * @return $this
     */
    public function setTranslator(TranslationRepositoryInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
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
     * @return bool
     */
    public function expectsLongNames()
    {
        return ! $this->brief;
    }
}