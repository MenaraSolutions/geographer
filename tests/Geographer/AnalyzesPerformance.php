<?php

namespace Tests;

trait AnalyzesPerformance
{
    /**
     * @var float Maximum execution time in seconds
     */
    protected $performanceTimeGoal = 60;

    /**
     * @var int Maximum memory usage in bytes
     */
    protected $performanceMemoryGoal = 10000000;

    /**
     * @var int
     */
    protected $memoryUsage = 0;

    /**
     * @var int
     */
    protected $startTimestamp;

    /**
     * @return void
     */
    public function performanceHook()
    {
        $this->memoryUsage = memory_get_usage();
        $this->startTimestamp = microtime(true);
    }

    /**
     * @return void
     */
    public function performanceCheck()
    {
        $this->assertTrue((microtime(true) - $this->startTimestamp) < $this->performanceTimeGoal);
        $this->assertTrue(memory_get_usage() - $this->memoryUsage < $this->performanceMemoryGoal);
    }
}
