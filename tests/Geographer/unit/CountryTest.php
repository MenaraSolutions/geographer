<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Divisible;
use MenaraSolutions\Geographer\Earth;

class CountryTest extends Test
{
    /**
     * @test
     */
    public function can_fetch_states_for_all_countries()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();

        foreach($countries as $country) {
            $states = $country->getStates();
            $this->assertEquals(MemberCollection::class, get_class($states));
            $this->assertTrue(is_array($states) || $states instanceof \ArrayObject);
            $array = $country->toArray();
            $this->assertTrue(is_array($array));
            $this->assertArrayHasKey('code', $array);
            $this->assertArrayHasKey('code_3', $array);
            $this->assertArrayHasKey('name', $array);
        }
    }

    /**
     * @test
     */
    public function country_can_ask_for_a_short_name()
    {
        $earth = new Earth();
        $russia = $earth->findOne(['code' => 'RU']);
        $longName = $russia->getName();
        $russia->useShortNames();
        $this->assertNotEquals($longName, $russia->getName());
    }

    /**
     * @test
     */
    public function lower_case_find_also_works()
    {
        $earth = new Earth();
        $russia = $earth->findOne(['code' => 'ru']);
        $this->assertInstanceOf(Country::class, $russia);
    }

    /**
     * @test
     */
    public function country_can_ask_for_inflicted_russian_name_with_and_without_prep()
    {
        $russia = (new Earth())->findOne(['code' => 'br'])->setLanguage('ru');
        $defaultName = $russia->getName();
        $russia->inflict('from')->useShortNames()->includePrepositions();
        $this->assertNotEquals($defaultName, $russia->getName());
        $this->assertNotEquals($russia->getName(), $russia->excludePrepositions()->getName());
    }
}