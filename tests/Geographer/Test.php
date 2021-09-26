<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class Test extends TestCase
{
    use AnalyzesPerformance;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->performanceHook();
        parent::setUp();
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $this->performanceCheck();
        parent::tearDown();
    }
}
