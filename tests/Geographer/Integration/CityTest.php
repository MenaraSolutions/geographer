<?php

namespace Tests;

use MenaraSolutions\Geographer\Earth;

class CityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Countries that don't have any big cities
     * @var array
     */
    protected $emptyCountries = [
        'AX', 'AS', 'AD', 'AI', 'AQ', 'AG', 'AW', 'BM', 'BQ', 'BV', 'IO', 'KY', 'CX', 'CC', 'KM',
        'CK', 'CW', 'DM', 'FK', 'FO', 'PF', 'TF', 'GI', 'GL', 'GD', 'GU', 'GG', 'HM', 'VA', 'IM',
        'JE', 'KI', 'LI', 'MT', 'MH', 'FM', 'MC', 'MS', 'NR', 'NU', 'NF', 'MP', 'PW', 'PN', 'BL',
        'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 'SM', 'SC', 'SX', 'GS', 'SJ', 'TK', 'TO', 'TC',
        'TV', 'UM', 'VU', 'VG', 'WF'
    ];

    /**
     * @test
     */
    public function most_states_of_all_countries_have_cities()
    {
        $planet = new Earth();
        $countries = $planet->getCountries();

        foreach ($countries as $country) {
            $citiesCount = 0;

            foreach ($country->getStates() as $state) {
                $cities = $state->getCities();

                foreach ($cities as $city) {
                    $citiesCount++;
                    $array = $city->toArray();
                    $this->assertTrue(isset($array['code']) && is_int($array['code']));
                    $this->assertTrue(isset($array['name']) && is_string($array['name']));

                    /*if ($country->getCode() == 'RU') {
                        $city->setLanguage('en')->inflict('from');
                        echo $city->getName() . "\n";
                    }*/
                }
            }

            if (! in_array($country->getCode(), $this->emptyCountries)) {
                $this->assertTrue($citiesCount > 0);
            }
        }
    }
}
