<?php

namespace MenaraSolutions\FluentGeonames\Traits;

use MenaraSolutions\FluentGeonames\Divisible;

/**
 * Class HasTranslations
 * @package MenaraSolutions\FluentGeonames
 */
trait HasTranslations
{
    /**
     * Default language is English
     *
     * @var string
     */
    protected $language = 'en';

    /**
     * @param $language
     * @return Divisible
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
     * @param $input
     * @return string
     */
    public function getText($input)
    {
        return $this->config->getTranslator()->translate($input, get_class($this), $this->language);
    }
}