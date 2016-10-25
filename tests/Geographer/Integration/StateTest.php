<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Earth;

class StateTest extends Test
{
    /**
     * @test
     */
    public function all_states_of_all_countries_have_geonames_ids_and_names()
    {
        $planet = (new Earth())->inflict('from')->setLocale('ru');
        $countries = $planet->getCountries();

        foreach ($countries as $country) {
            /**
             * @var MemberCollection $states
             */
            $states = $country->getStates();

            foreach ($states as $state) {
                $array = $state->toArray();
                //$this->assertTrue(isset($array['code']) && is_int($array['code']));
                $this->assertTrue(isset($array['name']) && is_string($array['name']));

                if ($country->getCode() == 'AF') {
                    //echo $state->inflict('default')->getName() . "\n";
                    //echo $state->inflict('from')->getName() . "\n";
                    //echo $state->inflict('in')->getName() . "\n";
                }
            }
        }
    }

    /**
     * 
     */
    public function all_states_have_iso_codes()
    {
        $codes = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/iso3166-2.csv');
        $line = strtok($codes, "\r\n");
        $countryData = [];

        while ($line !== false) {
            $line = str_replace('"', '', $line);
            list ($country, $name, $code) = explode(',', $line);
            if (! isset($countryData[$country])) $countryData[$country] = [];
            if ($code != '-') {
                $countryData[$country][] = [
                    'name' => $name,
                    'code' => $code
                ];
            }
            $line = strtok("\r\n");
        }

        $planet = (new Earth());
        $countries = $planet->getCountries();
        $guesses = 0;

        foreach ($countries as $country) {
            $filename = dirname(dirname(dirname(dirname(__FILE__)))) . '/resources/states/' . $country->getCode() . '.json';
            if (! file_exists($filename)) continue;
            $states = file_get_contents($filename);
            $states = json_decode($states, true);

            $counter = 0;

            $meta = isset($countryData[$country->getCode()]) ? $countryData[$country->getCode()] : [];

            foreach ($states as &$state) {
                if (!empty($state['ids']['iso_3166_2'])) $counter++;
                $name = $state['long']['default'];

                if (isset($state['ids']['iso_3166'])) unset($state['ids']['iso_3166']);

                if ($country->getFipsCode() && isset($state['ids']['fips']) && is_numeric($state['ids']['fips'])) {
                    $state['ids']['fips'] = $country->getFipsCode() . $state['ids']['fips'];
                }

                foreach ($meta as $oneMeta) {
                    similar_text($oneMeta['name'], $name, $percent);
                    if ($percent > 90) {
                        if (substr($oneMeta['code'], 0, 2) != $country->getCode()) continue;
                        //echo "Found a match for " . $name . " = " . $oneMeta['code'] . "\n";
                        $state['ids']['iso_3166_2'] = $oneMeta['code'];
                        $guesses++;
                    }
                }
            }

            $total = count($meta);
            if ($total == 0 || $counter >= $total) continue;
            echo "Country " . $country->getCode() . " has " . $counter . "/" . $total . " states\n";
        }

        echo "Guesses: " . $guesses . "\n";
    }
}
