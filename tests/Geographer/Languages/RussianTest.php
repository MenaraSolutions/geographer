<?php

namespace Tests\Languages;

use Tests\Test;
use MenaraSolutions\Geographer\Earth;

class RussianTest extends Test
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
            $this->assertNotEquals($country->setLocale($this->languageCode)->getName(), $country->setLocale('en')->getName());
            //echo $country->inflict('from')->setLocale($this->languageCode)->getName() . "\n";
        }
    }

    /**
     * 
     */
    public function specific_country_has_all_states()
    {
        $country = (new Earth())->findOneByCode('BG')->setLocale('ru');
        $states = $country->getStates();

        $array = [];
        $output = [
            'code' => "0",
            'long' => [
                'default' => ''
            ]
        ];

        foreach ($states as $state) {
            echo "id: " . $state->getCode() . " iso: " . $state->getIsoCode() .  " names: "
                . $state->inflict('default')->getName() . "  "
                . $state->inflict('in')->getName() . "  "
                . $state->inflict('from')->getName() . "\n";

            if (preg_match('/[A-Za-z]+/', $state->inflict('default')->getName())) {
                $output['code'] = strval($state->getCode());
                $output['long']['default'] = $state->getIsoCode();
                $array[] = $output;
            }
        }

        echo json_encode($array);
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
                if ($state->setLocale($this->languageCode)->getName() != $state->setLocale('en')->getName()) {
                    $translatedCount++;
                }
            }
        }

        $this->assertTrue(($translatedCount / $stateCount) * 100 > $this->threshold);
    }
}
