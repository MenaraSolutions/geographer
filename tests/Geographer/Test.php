<?php

namespace Tests;

use MenaraSolutions\Geographer\Earth;

abstract class Test extends \PHPUnit\Framework\TestCase
{
    use AnalyzesPerformance;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->performanceHook();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->performanceCheck();
        parent::tearDown();
    }
}
