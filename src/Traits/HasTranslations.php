<?php

namespace MenaraSolutions\FluentGeonames\Traits;

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
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}