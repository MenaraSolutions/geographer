<?php

namespace MenaraSolutions\FluentGeonames\Tests;

abstract class Test extends \PHPUnit_Framework_TestCase
{
    use AnalyzesPerformance;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->performanceHook();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->performanceCheck();
    }
}