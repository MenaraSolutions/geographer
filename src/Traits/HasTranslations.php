<?php

namespace MenaraSolutions\FluentGeonames\Traits;

use MenaraSolutions\FluentGeonames\Divisible;

/**
 * Class HasTranslations
 * @package MenaraSolutions\FluentGeonames
 */
trait HasTranslations
{
    public function translate($input, $language = null)
    {
        $translator = $this->config->getTranslator();
        $language = $language ?: $this->config->getLanguage();

        return $translator->translate($input, get_class($this), $language);
    }
}