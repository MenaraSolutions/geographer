<?php

namespace MenaraSolutions\FluentGeonames\Tests;

use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\Planet;

class PlanetTest extends \PHPUnit_Framework_TestCase
{
    const TOTAL_COUNTRIES = 249;

    /**
     * @test
     * @expectedException \MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException
     */
    public function nonexisting_config_path_throws_exception()
    {
        $config = $this->getMock('MenaraSolutions\FluentGeonames\Contracts\ConfigInterface', ['getStoragePath']);
        $config->expects($this->any())
            ->method('getStoragePath')
            ->willReturn(DIRECTORY_SEPARATOR . 'tmp22222' . DIRECTORY_SEPARATOR);

        $planet = new Planet($config);
    }

    /**
     * @test
     */
    public function planet_class_loads_default_countries()
    {
        $planet = new Planet();
        $countries = $planet->getCountries();
        $this->assertTrue(is_array($countries->toArray()));
        $this->assertEquals(self::TOTAL_COUNTRIES, count($countries));
        $this->assertEquals(self::TOTAL_COUNTRIES, count($countries->toArray()));
    }

    /**
     * @test
     */
    public function can_get_country_names_and_iso_codes_for_all_countries()
    {
        $planet = new Planet();
        $countries = $planet->getCountries();
        foreach($countries as $country) {
            $this->assertNotEmpty($country->getShortName());
            $this->assertNotEmpty($country->getLongName());
            $this->assertEquals(2, strlen($country->getCode()));
            $this->assertEquals(3, strlen($country->getCode(true)));
        }
    }
}