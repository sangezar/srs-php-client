<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\ResourceUsage;

class ResourceUsageTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'rusage' => [
                'ru_utime' => 1.5,      // User CPU time
                'ru_stime' => 0.5,      // System CPU time
                'ru_maxrss' => 102400,  // Maximum resident set size
                'ru_minflt' => 1000,    // Minor page faults
                'ru_majflt' => 10,      // Major page faults
                'ru_inblock' => 500,    // Block input operations
                'ru_oublock' => 300,    // Block output operations
                'ru_nvcsw' => 200,      // Voluntary context switches
                'ru_nivcsw' => 100,     // Involuntary context switches
                'ru_ixrss' => 5000,     // Shared memory size
                'ru_idrss' => 3000,     // Unshared data size
                'ru_isrss' => 2000,     // Unshared stack size
                'ru_nswap' => 50,       // Number of swaps
                'ru_msgsnd' => 150,     // Messages sent
                'ru_msgrcv' => 100,     // Messages received
                'ru_nsignals' => 25     // Signals received
            ]
        ]
    ];

    private ResourceUsage $usage;

    protected function setUp(): void
    {
        $this->usage = new ResourceUsage($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['code'], $this->usage->getCode());
        $this->assertEquals($this->testData['server'], $this->usage->getServer());
        $this->assertEquals($this->testData['service'], $this->usage->getService());
        $this->assertEquals($this->testData['pid'], $this->usage->getPid());
        $this->assertTrue($this->usage->isOk());
    }

    public function testCpuTime(): void
    {
        $this->assertEquals(1.5, $this->usage->getUserTime());
        $this->assertEquals(0.5, $this->usage->getSystemTime());
        $this->assertEquals(2.0, $this->usage->getTotalCpuTime());
    }

    public function testMemoryUsage(): void
    {
        $this->assertEquals(102400, $this->usage->getMaxRss());
        $this->assertEquals(5000, $this->usage->getSharedMemory());
        $this->assertEquals(3000, $this->usage->getUnsharedData());
        $this->assertEquals(2000, $this->usage->getUnsharedStack());
    }

    public function testPageFaults(): void
    {
        $this->assertEquals(1000, $this->usage->getMinorFaults());
        $this->assertEquals(10, $this->usage->getMajorFaults());
    }

    public function testIOOperations(): void
    {
        $this->assertEquals(500, $this->usage->getBlockInputOperations());
        $this->assertEquals(300, $this->usage->getBlockOutputOperations());
        $this->assertEquals(500, $this->usage->getInputOperations());
        $this->assertEquals(300, $this->usage->getOutputOperations());
    }

    public function testContextSwitches(): void
    {
        $this->assertEquals(200, $this->usage->getVoluntaryContextSwitches());
        $this->assertEquals(100, $this->usage->getInvoluntaryContextSwitches());
        $this->assertEquals(300, $this->usage->getTotalContextSwitches());
    }

    public function testSystemOperations(): void
    {
        $this->assertEquals(50, $this->usage->getSwaps());
        $this->assertEquals(150, $this->usage->getMessagesSent());
        $this->assertEquals(100, $this->usage->getMessagesReceived());
        $this->assertEquals(25, $this->usage->getSignalsReceived());
    }

    public function testEmptyData(): void
    {
        $usage = new ResourceUsage([]);
        
        // Basic information
        $this->assertEquals(0, $usage->getCode());
        $this->assertEquals('', $usage->getServer());
        $this->assertEquals('', $usage->getService());
        $this->assertEquals('', $usage->getPid());
        $this->assertTrue($usage->isOk());
        
        // CPU time
        $this->assertEquals(0.0, $usage->getUserTime());
        $this->assertEquals(0.0, $usage->getSystemTime());
        $this->assertEquals(0.0, $usage->getTotalCpuTime());
        
        // Memory
        $this->assertEquals(0, $usage->getMaxRss());
        $this->assertEquals(0, $usage->getSharedMemory());
        $this->assertEquals(0, $usage->getUnsharedData());
        $this->assertEquals(0, $usage->getUnsharedStack());
        
        // Page faults
        $this->assertEquals(0, $usage->getMinorFaults());
        $this->assertEquals(0, $usage->getMajorFaults());
        
        // I/O operations
        $this->assertEquals(0, $usage->getBlockInputOperations());
        $this->assertEquals(0, $usage->getBlockOutputOperations());
        
        // Context switches
        $this->assertEquals(0, $usage->getVoluntaryContextSwitches());
        $this->assertEquals(0, $usage->getInvoluntaryContextSwitches());
        $this->assertEquals(0, $usage->getTotalContextSwitches());
        
        // System operations
        $this->assertEquals(0, $usage->getSwaps());
        $this->assertEquals(0, $usage->getMessagesSent());
        $this->assertEquals(0, $usage->getMessagesReceived());
        $this->assertEquals(0, $usage->getSignalsReceived());
    }

    public function testErrorCode(): void
    {
        $errorData = array_merge($this->testData, ['code' => 1]);
        $errorUsage = new ResourceUsage($errorData);
        
        $this->assertEquals(1, $errorUsage->getCode());
        $this->assertFalse($errorUsage->isOk());
    }
} 