<?php

namespace MenaraSolutions\FluentGeonames;

use MenaraSolutions\FluentGeonames\Contracts\DivisionInterface;
use MenaraSolutions\FluentGeonames\Contracts\TranslationRepositoryInterface;
use MenaraSolutions\FluentGeonames\Traits\HasTranslations;

/**
 * Class Country
 * @package MenaraSolutions\FluentGeonames
 */
class Country implements DivisionInterface
{
    use HasTranslations;

    /**
     * @var \stdClass
     */
    private $config;

    /**
     * @var TranslationRepositoryInterface 
     */
    private $translator;

    /**
     * Country constructor.
     * @param \stdClass $config
     * @param TranslationRepositoryInterface $translator
     */
    public function __construct(\stdClass $config, TranslationRepositoryInterface $translator)
    {
        $this->config = $config;
        $this->translator = $translator;
    }

    /**
     * Get alpha2 ISO code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->config->iso_3611[0];
    }

    /**
     * Get alpha3 ISO code
     *
     * @return string
     */
    public function getCode3()
    {
        return $this->config->iso_3611[1];
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->getText($this->config->names->short) ?: $this->getText($this->config->names->long);
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->getText($this->config->names->long) ?: $this->getText($this->config->names->short);
    }

    /**
     * @param $input
     * @return string
     */
    public function getText($input)
    {
        return $this->translator->translate($input, get_class($this), $this->language);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'code_3' => $this->getCode3(),
            'short_name' => $this->getShortName(),
            'long_name' => $this->getLongName()
        ];
    }
}