<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Earth;

class EarthTest extends Test
{
    const TOTAL_COUNTRIES = 249;

    /**
     * @test
     */
    public function planet_class_loads_default_countries()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();
        $this->assertInternalType('array', $countries->toArray());
        $this->assertCount(self::TOTAL_COUNTRIES, $countries);
        $this->assertCount(self::TOTAL_COUNTRIES, $countries->toArray());
    }

    /**
     * @test
     */
    public function can_get_country_names_and_iso_codes_for_all_countries()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();
        foreach($countries as $country) {
            $this->assertNotEmpty($country->getShortName());
            $this->assertNotEmpty($country->getLongName());
            $this->assertEquals(2, strlen($country->getCode()));
            $this->assertEquals(3, strlen($country->getCode3()));
        }
    }

    /**
     * @test
     */
    public function can_get_translated_country_names()
    {
        $earth = new Earth();
        $country = $earth->findOne(['code' => 'ru']);
        $original = $country->getLongName();
        $country->setLocale('ru');
        $this->assertTrue(!empty($country->getLongName()));
        $this->assertNotEquals($original, $country->getLongName());
    }

    /**
     * @test
     */
    public function can_find_a_country_by_code()
    {
        $earth = new Earth();
        $russia = $earth->findOne(['code' => 'RU']);
        $this->assertInstanceOf(Country::class, $russia);
        $this->assertEquals('RU', $russia->getCode());
    }

    /**
     * @test
     */
    public function can_get_country_lists_by_continents()
    {
        $earth = new Earth();

        $continents = ['Europe', 'Oceania', 'NorthAmerica', 'SouthAmerica', 'Asia', 'Africa'];

        foreach ($continents as $continent)
        {
            $continent = $earth->{'get' . $continent}();
            $this->assertInstanceOf(MemberCollection::class, $continent);
            $this->assertInternalType('array', $continent->toArray());
        }
    }

    /**
     * @test
     */
    public function can_filter_the_countries()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();
        $count = $countries->count();

        $countries = $countries->filter(function($item) {
            return $item->getPopulation() > 50000;
        });

        $this->assertNotEquals($count, $countries->count());
    }
}
