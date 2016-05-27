<?php

namespace Tests;

use MenaraSolutions\Geographer\Earth;

class Russian extends Test
{
    protected $forms = ['', 'to', 'from'];

    /**
     * @test
     */
    public function all_countries_have_russian_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();

        foreach($countries as $country) {
            $this->assertNotEquals($country->setLanguage('ru')->getName(), $country->setLanguage('en')->getName());
            echo $country->setLanguage('ru')->inflict('from')->getName() . "\n";
        }
    }
}
