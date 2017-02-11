<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Services\DefaultManager;
use MenaraSolutions\Geographer\State;

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

                if ($country->getContinent() == 'EU') {
                    //echo $state->inflict('default')->getName() . "\n";
                    //echo $state->inflict('from')->getName() . "\n";
                    //echo $state->inflict('in')->getName() . "\n";
                }
            }
        }
    }

    /**
     * @test
     */
    public function state_can_be_built_from_iso_code()
    {
        $voronezh = State::build('RU-VOR');
        $this->assertInstanceOf(State::class, $voronezh);
        $this->assertNotEmpty($voronezh->getName());
    }

    /**
     * @test
     */
    public function parent_method_can_traverse_up_to_a_planet()
    {
        $voronezh = State::build('RU-VOR');
        $russia = $voronezh->parent();
        $this->assertInstanceOf(Country::class, $russia);
        $this->assertNotEmpty($russia->getName());

        $planet = $russia->parent();
        $this->assertInstanceOf(Earth::class, $planet);
        $this->assertNotEmpty($planet->getName());
    }

    /**
     *
     */
    public function count_states_with_iso_codes()
    {
        $planet = (new Earth());
        $countries = $planet->getCountries()->sortBy('code');

        echo "\n";
        foreach ($countries as $country) {
            /**
             * @var MemberCollection $states
             */
            $states = $country->getStates();
            echo "Country " . $country->getCode() . ' has ' . count($states) . " states\n";
        }
    }

    /**
     * 
     */
    public function all_countries_got_correct_fips_state_count()
    {
        $input = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/fips-414.txt');
        $line = strtok($input, "\r\n");
        $isoCounters = [];

        while ($line !== false) {
            list($code, $garbage) = explode("_", $line);

            $countryCode = substr($code, 0, 2);
            $stateCode = substr($code, 2);

            if ($stateCode == "00") {
                $isoCounters[$countryCode] = 0;
            } else {
                $isoCounters[$countryCode]++;
            }

            $line = strtok("\r\n");
        }

        $planet = (new Earth());
        $countries = $planet->setStandard('fips')->getCountries()->sortBy('code');

        foreach ($countries as $country) {
            if (! in_array($country->getFipsCode(), array_keys($isoCounters))) echo 'Missing country: ' . $country->getFipsCode() . "\n";
        }

        foreach ($isoCounters as $key => $value) {
            if (! $countries->findOne(['fipsCode' => $key])) {
                echo 'Country ' . $key . " doesnt exist\n";
            }
        }
        //$this->assertEquals(count($countries), count($isoCounters));

        foreach ($countries as $country) {
            /**
             * @var MemberCollection $states
             */
            $states = $country->getStates();
            $count = strval(count($states));

            if ($count == $isoCounters[$country->getFipsCode()]) {

            } else {
                echo "State count mismatch for " . $country->getFipsCode() . ": ${count} not in ".json_encode($isoCounters[$country->getFipsCode()])." \n";
            }
        }

    }

    /**
     * @test
     */
    public function all_countries_got_correct_iso_state_count()
    {
        $input = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/iso3166_table.txt');
        $line = strtok($input, "\r\n");
        $isoCounters = [];

        while ($line !== false) {
            list($code, $name, $counters) = explode("\t", $line);

            preg_match_all('/(\d+)/', $counters, $numbers);

            if (empty($numbers[0])) {
                $numbers[0][] = "0";
            } else if (count($numbers[0]) > 1) {
                $sum = 0;

                foreach ($numbers[0] as $number) { $sum += intval($number); }

                $numbers[0][] = strval($sum);

                if (count($numbers[0]) > 2) { $numbers[0][] = $numbers[0][0] + $numbers[0][1]; }
            }

            $isoCounters[$code] = $numbers[0];
            unset($numbers);
            $line = strtok("\r\n");
        }

        $planet = (new Earth());
        $countries = $planet->getCountries()->sortBy('code');
        $this->assertEquals(count($countries), count($isoCounters));

        foreach ($countries as $country) {
            /**
             * @var MemberCollection $states
             */
            $states = $country->getStates();
            $count = strval(count($states));

            if (in_array($count, $isoCounters[$country->getCode()])) {

            } else {
                echo "State count mismatch for " . $country->getCode() . ": ${count} not in ".json_encode($isoCounters[$country->getCode()])." \n";
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
                if (!isset($state['ids']['iso_3166_2'])) $counter++;
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
            echo "Country " . $country->getCode() . " has " . $counter . "/" . $total . " states\n";
            if ($total == 0 || $counter >= $total) continue;
        }

        echo "Guesses: " . $guesses . "\n";
    }
}
