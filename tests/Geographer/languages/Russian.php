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
        }
    }

    /**
     * @test
     */
    public function many_states_have_russian_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();
        $threshold = 50; // 50% is ok for now
        $stateCount = 0;
        $translatedCount = 0;

        foreach($countries as $country) {
            $states = $country->getStates();
            $stateCount =+ count($states);

            foreach($states as $state) {
                if ($state->setLanguage('ru')->getName() != $state->setLanguage('en')->getName()) {
                    $translatedCount++;
                }
            }
        }

        $this->assertTrue(($translatedCount / $stateCount) * 100 > $threshold);
    }
}
