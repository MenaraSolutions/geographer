<?php

namespace MenaraSolutions\FluentGeonames\Services;

use MenaraSolutions\FluentGeonames\Contracts\ConfigInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationAgencyInterface;

/**
 * Class DefaultConfig
 * @package MenaraSolutions\FluentGeonames\Services
 */
class DefaultConfig implements ConfigInterface
{
    /**
     * @var TranslationAgencyInterface $translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * @var string
     */
    protected $form;

    /**
     * @var bool
     */
    protected $brief = false;

    /**
     * @var string
     */
    protected $path;

    /**
     * DefaultConfig constructor.
     * @param string $path
     * @param TranslationAgencyInterface $translator
     */
    public function __construct($path = null, TranslationAgencyInterface $translator = null)
    {
        $this->path = $path ?: dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        $this->translator = $translator ?: new TranslationAgency($this->path);
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
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = strtolower(substr($language, 0, 2));

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