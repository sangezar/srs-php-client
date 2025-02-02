<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\ProcessStats;

class ProcessStatsTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'process' => [
                'uptime' => 60000,           // 60 seconds
                'cpu_percent' => 25.5,       // CPU usage
                'mem_kb' => 102400,          // Memory usage in KB
                'mem_percent' => 10.5,       // Memory usage percentage
                'comm' => 'srs',             // Command name
                'state' => 'S',              // Process state
                'ppid' => 1,                 // Parent PID
                'pgrp' => 1000,             // Process group
                'session' => 1001,           // Session ID
                'tty_nr' => 2,              // TTY number
                'tpgid' => 1002,            // TPGID
                'ru_utime' => 1.5,          // User CPU time
                'ru_stime' => 0.5,          // System CPU time
                'ru_cutime' => 0.3,         // Children user CPU time
                'ru_cstime' => 0.2,         // Children system CPU time
                'ru_minflt' => 1000,        // Minor page faults
                'ru_majflt' => 10,          // Major page faults
                'ru_cminflt' => 500,        // Children minor page faults
                'ru_cmajflt' => 5,          // Children major page faults
                'ru_inblock' => 200,        // Block input operations
                'ru_oublock' => 100,        // Block output operations
                'ru_nvcsw' => 150,          // Voluntary context switches
                'ru_nivcsw' => 50,          // Involuntary context switches
                'ru_maxrss' => 204800       // Maximum resident set size
            ],
            'threads' => [
                [
                    'id' => 1,
                    'cpu_percent' => 15.5,
                    'state' => 'R'
                ],
                [
                    'id' => 2,
                    'cpu_percent' => 10.0,
                    'state' => 'S'
                ]
            ]
        ]
    ];

    private ProcessStats $stats;

    protected function setUp(): void
    {
        $this->stats = new ProcessStats($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['code'], $this->stats->getCode());
        $this->assertEquals($this->testData['server'], $this->stats->getServer());
        $this->assertEquals($this->testData['service'], $this->stats->getService());
        $this->assertEquals($this->testData['pid'], $this->stats->getPid());
        $this->assertTrue($this->stats->isOk());
    }

    public function testProcessInfo(): void
    {
        $this->assertEquals(60000, $this->stats->getUptime());
        $this->assertEquals(60.0, $this->stats->getUptimeSeconds());
        $this->assertEquals(25.5, $this->stats->getCpuPercent());
        $this->assertEquals(102400, $this->stats->getMemoryKb());
        $this->assertEquals(10.5, $this->stats->getMemoryPercent());
        $this->assertEquals('srs', $this->stats->getCommand());
        $this->assertEquals('S', $this->stats->getState());
    }

    public function testProcessIds(): void
    {
        $this->assertEquals(12345, $this->stats->getProcessId());
        $this->assertEquals(1, $this->stats->getParentPid());
        $this->assertEquals(1000, $this->stats->getProcessGroup());
        $this->assertEquals(1001, $this->stats->getSession());
        $this->assertEquals(2, $this->stats->getTtyNr());
        $this->assertEquals(1002, $this->stats->getTpgid());
    }

    public function testCpuTime(): void
    {
        $this->assertEquals(1.5, $this->stats->getUserTime());
        $this->assertEquals(0.5, $this->stats->getSystemTime());
        $this->assertEquals(0.3, $this->stats->getChildrenUserTime());
        $this->assertEquals(0.2, $this->stats->getChildrenSystemTime());
        $this->assertEquals(2.0, $this->stats->getTotalCpuTime());
    }

    public function testPageFaults(): void
    {
        $this->assertEquals(1000, $this->stats->getMinorFaults());
        $this->assertEquals(10, $this->stats->getMajorFaults());
        $this->assertEquals(500, $this->stats->getChildrenMinorFaults());
        $this->assertEquals(5, $this->stats->getChildrenMajorFaults());
    }

    public function testIOOperations(): void
    {
        $this->assertEquals(200, $this->stats->getBlockInputOperations());
        $this->assertEquals(100, $this->stats->getBlockOutputOperations());
    }

    public function testContextSwitches(): void
    {
        $this->assertEquals(150, $this->stats->getVoluntaryContextSwitches());
        $this->assertEquals(50, $this->stats->getInvoluntaryContextSwitches());
        $this->assertEquals(200, $this->stats->getTotalContextSwitches());
    }

    public function testMemory(): void
    {
        $this->assertEquals(204800, $this->stats->getMaxRss());
    }

    public function testThreads(): void
    {
        $this->assertEquals(2, $this->stats->getThreadCount());
        
        // Check first thread information
        $this->assertEquals(15.5, $this->stats->getThreadCpuPercent(1));
        $this->assertEquals('R', $this->stats->getThreadState(1));
        
        // Check second thread information
        $this->assertEquals(10.0, $this->stats->getThreadCpuPercent(2));
        $this->assertEquals('S', $this->stats->getThreadState(2));
        
        // Check non-existent thread
        $this->assertEquals(0.0, $this->stats->getThreadCpuPercent(999));
        $this->assertEquals('', $this->stats->getThreadState(999));
    }

    public function testEmptyData(): void
    {
        $stats = new ProcessStats([]);
        
        // Basic information
        $this->assertEquals(0, $stats->getCode());
        $this->assertEquals('', $stats->getServer());
        $this->assertEquals('', $stats->getService());
        $this->assertEquals('', $stats->getPid());
        $this->assertTrue($stats->isOk());
        
        // Process information
        $this->assertEquals(0, $stats->getUptime());
        $this->assertEquals(0.0, $stats->getUptimeSeconds());
        $this->assertEquals(0.0, $stats->getCpuPercent());
        $this->assertEquals(0, $stats->getMemoryKb());
        $this->assertEquals(0.0, $stats->getMemoryPercent());
        $this->assertEquals('', $stats->getCommand());
        $this->assertEquals('', $stats->getState());
        
        // Process IDs
        $this->assertEquals(0, $stats->getProcessId());
        $this->assertEquals(0, $stats->getParentPid());
        $this->assertEquals(0, $stats->getProcessGroup());
        $this->assertEquals(0, $stats->getSession());
        $this->assertEquals(0, $stats->getTtyNr());
        $this->assertEquals(0, $stats->getTpgid());
        
        // CPU time
        $this->assertEquals(0.0, $stats->getUserTime());
        $this->assertEquals(0.0, $stats->getSystemTime());
        $this->assertEquals(0.0, $stats->getChildrenUserTime());
        $this->assertEquals(0.0, $stats->getChildrenSystemTime());
        
        // Page faults
        $this->assertEquals(0, $stats->getMinorFaults());
        $this->assertEquals(0, $stats->getMajorFaults());
        $this->assertEquals(0, $stats->getChildrenMinorFaults());
        $this->assertEquals(0, $stats->getChildrenMajorFaults());
        
        // I/O operations
        $this->assertEquals(0, $stats->getBlockInputOperations());
        $this->assertEquals(0, $stats->getBlockOutputOperations());
        
        // Context switches
        $this->assertEquals(0, $stats->getVoluntaryContextSwitches());
        $this->assertEquals(0, $stats->getInvoluntaryContextSwitches());
        $this->assertEquals(0, $stats->getTotalContextSwitches());
        
        // Memory
        $this->assertEquals(0, $stats->getMaxRss());
        
        // Threads
        $this->assertEquals(0, $stats->getThreadCount());
        $this->assertEquals(0.0, $stats->getThreadCpuPercent(1));
        $this->assertEquals('', $stats->getThreadState(1));
    }

    public function testErrorCode(): void
    {
        $errorData = array_merge($this->testData, ['code' => 1]);
        $errorStats = new ProcessStats($errorData);
        
        $this->assertEquals(1, $errorStats->getCode());
        $this->assertFalse($errorStats->isOk());
    }

    public function testAdvancedProcessInfo(): void
    {
        $advancedData = array_merge($this->testData, [
            'data' => array_merge($this->testData['data'], [
                'process' => array_merge($this->testData['data']['process'], [
                    'guest_time' => 0.5,
                    'cguest_time' => 0.3,
                    'vsize' => 4194304,
                    'rss' => 1024,
                    'rsslim' => 8192,
                    'priority' => 20,
                    'nice' => 0,
                    'num_threads' => 4,
                    'rt_priority' => 1,
                    'policy' => 0,
                    'startcode' => 0x1000,
                    'endcode' => 0x2000,
                    'startstack' => 0x3000,
                    'signal' => 0,
                    'blocked' => 0,
                    'sigignore' => 0,
                    'sigcatch' => 0,
                    'exit_signal' => 0
                ])
            ])
        ]);

        $stats = new ProcessStats($advancedData);

        $this->assertEquals(0.5, $stats->getGuestTime());
        $this->assertEquals(0.3, $stats->getCguestTime());
        $this->assertEquals(4194304, $stats->getVirtualMemorySize());
        $this->assertEquals(1024, $stats->getRss());
        $this->assertEquals(8192, $stats->getRssLimit());
        $this->assertEquals(20, $stats->getPriority());
        $this->assertEquals(0, $stats->getNice());
        $this->assertEquals(4, $stats->getNumThreads());
        $this->assertEquals(1, $stats->getRtPriority());
        $this->assertEquals(0, $stats->getPolicy());
        $this->assertEquals(0x1000, $stats->getStartCode());
        $this->assertEquals(0x2000, $stats->getEndCode());
        $this->assertEquals(0x3000, $stats->getStartStack());
        $this->assertEquals(0, $stats->getSignal());
        $this->assertEquals(0, $stats->getBlocked());
        $this->assertEquals(0, $stats->getSigignore());
        $this->assertEquals(0, $stats->getSigcatch());
        $this->assertEquals(0, $stats->getExitSignal());
    }
} 