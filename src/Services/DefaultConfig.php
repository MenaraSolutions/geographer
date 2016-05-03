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
     * DefaultConfig constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->path = $params['path'] ?? dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        $this->translator = $params['translator'] ?? new TranslationRepository();
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
}