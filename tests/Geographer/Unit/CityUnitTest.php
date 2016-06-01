<?php

namespace Tests;

use MenaraSolutions\Geographer\City;

class CityUnitTest extends Test
{
    /**
     * @test
     */
    public function city_can_be_instantiated_based_on_code()
    {
        $city = City::build(2761369);
        $this->assertInstanceOf(City::class, $city);
    	$this->assertNotEmpty($city->getName());
    }
}
