<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\Divisible;
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Services\TranslationAgency;

class CountryTest extends Test
{
    /**
     * @test
     */
    public function can_fetch_states_for_all_countries()
    {
        $earth = new Earth();
        $countries = $earth->withoutMicro()->setLocale('ru')->sortBy('name');

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
            $this->assertNotEmpty($country->getNumericCode());
            $country->inflict('in');
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
    public function magic_getter_for_findone_works()
    {
        $earth = new Earth();
        $russia = $earth->findOneByCode('RU');
        $this->assertInstanceOf(Country::class, $russia);
        $this->assertNotEmpty($russia->getName());
    }

    /**
     * @test
     */
    public function country_can_be_built_from_iso_code()
    {
        $russia = Country::build('RU');
        $this->assertInstanceOf(Country::class, $russia);
        $this->assertNotEmpty($russia->getName());
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
        $russia = (new Earth())->findOne(['code' => 'br'])->setLocale('ru');
        $defaultName = $russia->getName();
        $russia->inflict(TranslationAgency::FORM_IN)->useShortNames()->includePrepositions();
        $this->assertNotEquals($defaultName, $russia->getName());
        $this->assertNotEquals($russia->getName(), $russia->excludePrepositions()->getName());
    }
}
