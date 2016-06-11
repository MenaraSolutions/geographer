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
        $countries = $earth->withoutMicro()->setLanguage('ru')->sortBy('name');

        foreach($countries as $country) {
            $states = $country->getStates();
            $this->assertEquals(MemberCollection::class, get_class($states));
            $this->assertTrue(is_array($states) || $states instanceof \ArrayObject);
            $array = $country->toArray();
            $this->assertTrue(is_array($array));
            $this->assertArrayHasKey('code', $array);
            $this->assertArrayHasKey('code3', $array);
            $this->assertArrayHasKey('name', $array);
            $this->assertNotEmpty($country->getContinent());
            //echo $country->getShortName() . "\n";
            //echo $country->getLongName() . "\n";
            //echo $array['name'] . "\n";
        }
    }

    /**
     * @test
     */
    public function country_can_ask_for_a_short_name()
    {
        $earth = new Earth();
        $russia = $earth->findOne(['code' => 'RU']);
        $longName = $russia->useLongNames()->getName();
        $russia->useShortNames();
        $this->assertNotEquals($longName, $russia->getName());
    }

    /**
     * @test
     */
    public function data_can_be_accesses_as_properties_and_as_array_keys()
    {
        $earth = new Earth();
        $russia = $earth->findOne(['code' => 'RU']);
        $name = $russia->getName();
        $this->assertEquals($name, $russia->name);
        $this->assertEquals($name, $russia['name']);
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
