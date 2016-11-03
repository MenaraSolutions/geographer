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
     * @test
     */
    public function calculate_state_translation_coverage()
    {
        $planet = (new Earth())->setLocale('ru')->setStandard('iso');

        $total = 0;
        $translated = 0;

        foreach ($planet->getCountries() as $country) {
            $states = $country->getStates();

            foreach ($states as $state) {
                $total++;

                if (preg_match('/[A-Za-z]+/', $state->inflict('default')->setLocale('ru')->getName())) {
                } else {
                    $translated++;
                }
            }

        }

        echo "Russian translation coverage for ISO states: " . round(($translated / $total) * 100) . "%\n";
    }

    /**
     *
     */
    public function all_iso_states_have_russian_translations()
    {
        $planet = (new Earth())->setLocale('ru')->setStandard('iso');

        // Parse translations
        $input = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/ru.txt');
        $line = strtok($input, "\r\n");
        $translations = [];

        while ($line !== false) {
            list($code, $translation, $garbage) = explode("\t", $line);
            $translations[$code] = $translation;
            $line = strtok("\r\n");
        }


        $array = [];
        $output = [
            'code' => "0",
            'long' => [
                'default' => ''
            ]
        ];

        foreach ($planet->getCountries() as $country) {
            $states = $country->getStates();

            $hasFailures = false;

            foreach ($states as $state) {
                if (preg_match('/[A-Za-z]+/', $state->inflict('default')->setLocale('ru')->getName())) {
                    $output['code'] = strval($state->getGeonamesCode());
                    $output['long']['default'] = $translations[strval($state->getIsoCode())];
                    $array[] = $output;
                    $hasFailures = true;
                }
            }

            if ($hasFailures) break;
        }

        echo json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     */
    public function specific_country_has_all_states()
    {
        $country = (new Earth())->findOneByCode('BO')->setLocale('ru');
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
