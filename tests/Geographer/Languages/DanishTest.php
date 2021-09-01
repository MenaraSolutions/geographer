<?php

namespace Tests\Languages;

use Tests\Test;
use MenaraSolutions\Geographer\Earth;

class DanishTest extends Test
{
    protected $forms = ['', 'to', 'from'];

    /**
     * @var string
     */
    protected $languageCode = 'da';

    /**
     * @test
     */
    public function all_countries_have_translated_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();

        foreach($countries as $country) {
            $this->assertNotEquals($country->setLocale($this->languageCode)->getName(), $country->setLocale('en')->getName());
        }
    }
}
