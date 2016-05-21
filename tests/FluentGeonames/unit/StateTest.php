<?php

namespace Tests;

use MenaraSolutions\FluentGeonames\Collections\MemberCollection;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\Earth;

class StateTest extends Test
{
    /**
     * @test
     */
    public function all_states_of_all_countries_have_geonames_ids_and_names()
    {
        $planet = new Earth();
        $countries = $planet->getCountries();

        foreach ($countries as $country) {
            /**
             * @var MemberCollection $states
             */
            $states = $country->getStates();

            foreach ($states as $state) {
                $array = $state->toArray();
                $this->assertTrue(isset($array['code']) && is_int($array['code']));
                $this->assertTrue(isset($array['name']) && is_string($array['name']));
            }
        }
    }
}