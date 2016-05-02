<?php

namespace MenaraSolutions\FluentGeonames\Tests;

use MenaraSolutions\FluentGeonames\Collections\DivisionCollection;
use MenaraSolutions\FluentGeonames\Country;
use MenaraSolutions\FluentGeonames\Exceptions\MisconfigurationException;
use MenaraSolutions\FluentGeonames\Planet;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Country;
     */
    protected $country;

    public function setUp()
    {
        $countries = (new Planet())->getCountries();
        $this->country = $countries[array_rand($countries->toArray())];
    }

    /**
     * @test
     */
    public function can_fetch_states_for_a_random_country()
    {
        $states = $this->country->getStates();
        $this->assertEquals(DivisionCollection::class, get_class($states));
    }
}