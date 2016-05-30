<?php

namespace Tests\Languages;

use Tests\Test;
use MenaraSolutions\Geographer\Earth;

class Russian extends Test
{
    protected $forms = ['', 'to', 'from'];

    /**
     * @var string
     */
    protected $languageCode = 'ru';

    /**
     * @var int
     */
    protected $threshold = 50;

    /**
     * @test
     */
    public function all_countries_have_translated_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();

        foreach($countries as $country) {
            $this->assertNotEquals($country->setLanguage($this->languageCode)->getName(), $country->setLanguage('en')->getName());
//            echo $country->inflict('from')->setLanguage($this->languageCode)->getName() . "\n";
        }
    }

    /**
     * @test
     */
    public function many_states_have_translated_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();
        $stateCount = 0;
        $translatedCount = 0;

        foreach($countries as $country) {
            $states = $country->getStates();
            $stateCount =+ count($states);

            foreach($states as $state) {
                if ($state->setLanguage($this->languageCode)->getName() != $state->setLanguage('en')->getName()) {
                    $translatedCount++;
                }
            }
        }

        $this->assertTrue(($translatedCount / $stateCount) * 100 > $this->threshold);
    }
}
