<?php

namespace Tests;

use MenaraSolutions\Geographer\Collections\MemberCollection;
use MenaraSolutions\Geographer\Country;
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
        $planet = new Earth();
        $russia = $planet->find(['code' => 'RU']);
        $longName = $russia->getName();
        $russia->useShortNames();
        $this->assertNotEquals($longName, $russia->getName());
    }
}