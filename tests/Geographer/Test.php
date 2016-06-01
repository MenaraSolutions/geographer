<?php

namespace Tests;

use MenaraSolutions\Geographer\Earth;

abstract class Test extends \PHPUnit_Framework_TestCase
{
    use AnalyzesPerformance;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->performanceHook();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $this->performanceCheck();
        parent::tearDown();
    }
}