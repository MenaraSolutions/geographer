<?php

namespace Tests\Languages;

/**
 * Class Spanish
 * @package Tests
 */
class Spanish extends Russian
{
    protected $languageCode = 'es';

    protected $threshold = 10;

    /**
     */
    public function all_countries_have_translated_names()
    {
    }
}
