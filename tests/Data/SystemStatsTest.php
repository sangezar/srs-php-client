<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\SystemStats;

class SystemStatsTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'cpu' => [
                'percent' => 25.5,
                'load_1' => 1.5,
                'load_5' => 1.2,
                'load_15' => 1.0,
                'user' => 1000,
                'nice' => 200,
                'sys' => 500,
                'idle' => 8000,
                'iowait' => 100,
                'irq' => 50,
                'softirq' => 30,
                'steal' => 20,
                'guest' => 10
            ],
            'network' => [
                'bytes_send' => 1024000,
                'bytes_recv' => 2048000,
                'bytes_send_delta' => 1024,
                'bytes_recv_delta' => 2048
            ],
            'disk' => [
                'read_bytes' => 512000,
                'write_bytes' => 256000,
                'read_bytes_delta' => 512,
                'write_bytes_delta' => 256,
                'busy' => 45.5
            ]
        ]
    ];

    private SystemStats $stats;

    protected function setUp(): void
    {
        $this->stats = new SystemStats($this->testData);
    }

    public function testBasicInfo(): void
    {
        // Basic information
        $this->assertEquals($this->testData['server'], $this->stats->getServer());
        $this->assertEquals($this->testData['service'], $this->stats->getService());
        $this->assertEquals($this->testData['pid'], $this->stats->getPid());
        $this->assertTrue($this->stats->isOk());
    }

    public function testCpuInfo(): void
    {
        // CPU information
        $this->assertEquals($this->testData['data']['cpu']['user'], $this->stats->getUserTime());
        $this->assertEquals($this->testData['data']['cpu']['nice'], $this->stats->getNice());
        $this->assertEquals($this->testData['data']['cpu']['sys'], $this->stats->getSystemTime());
    }

    public function testNetworkInfo(): void
    {
        // Network information
        $this->assertEquals($this->testData['data']['cpu']['iowait'], $this->stats->getIoWait());
        $this->assertEquals($this->testData['data']['cpu']['irq'], $this->stats->getIrq());
        $this->assertEquals($this->testData['data']['cpu']['softirq'], $this->stats->getSoftIrq());
    }

    public function testCpuLoadInfo(): void
    {
        $this->assertEquals(25.5, $this->stats->getCpuPercent());
        $this->assertEquals(1.5, $this->stats->getCpuLoadAverage1());
        $this->assertEquals(1.2, $this->stats->getCpuLoadAverage5());
        $this->assertEquals(1.0, $this->stats->getCpuLoadAverage15());
    }

    public function testDiskInfo(): void
    {
        $this->assertEquals(512000, $this->stats->getDiskReadBytesTotal());
        $this->assertEquals(256000, $this->stats->getDiskWriteBytesTotal());
        $this->assertEquals(512, $this->stats->getDiskReadBytesPerSecond());
        $this->assertEquals(256, $this->stats->getDiskWriteBytesPerSecond());
        $this->assertEquals(45.5, $this->stats->getDiskUsagePercent());
    }

    public function testCpuCalculations(): void
    {
        // Total time: 1000 + 200 + 500 + 8000 + 100 + 50 + 30 + 20 + 10 = 9910
        $this->assertEquals(9910, $this->stats->getTotalTime());
        
        // Active time: 9910 - 8000 - 100 = 1810
        $this->assertEquals(1810, $this->stats->getActiveTime());
        
        // Usage percentage: (1810 / 9910) * 100 ≈ 18.26
        $this->assertEquals(18.26, $this->stats->getCpuUsagePercent());
        
        // Check getPercent() alias
        $this->assertEquals(25.5, $this->stats->getPercent());
    }

    public function testEmptyData(): void
    {
        $stats = new SystemStats([]);
        
        // Basic information
        $this->assertEquals(0, $stats->getCode());
        $this->assertEquals('', $stats->getServer());
        $this->assertEquals('', $stats->getService());
        $this->assertEquals('', $stats->getPid());
        $this->assertTrue($stats->isOk());
        
        // CPU information
        $this->assertEquals(0.0, $stats->getCpuPercent());
        $this->assertEquals(0.0, $stats->getCpuLoadAverage1());
        $this->assertEquals(0.0, $stats->getCpuLoadAverage5());
        $this->assertEquals(0.0, $stats->getCpuLoadAverage15());
        
        // Network information
        $this->assertEquals(0, $stats->getNetworkSendBytesTotal());
        $this->assertEquals(0, $stats->getNetworkRecvBytesTotal());
        $this->assertEquals(0, $stats->getNetworkSendBytesPerSecond());
        $this->assertEquals(0, $stats->getNetworkRecvBytesPerSecond());
        
        // Disk information
        $this->assertEquals(0, $stats->getDiskReadBytesTotal());
        $this->assertEquals(0, $stats->getDiskWriteBytesTotal());
        $this->assertEquals(0, $stats->getDiskReadBytesPerSecond());
        $this->assertEquals(0, $stats->getDiskWriteBytesPerSecond());
        $this->assertEquals(0.0, $stats->getDiskUsagePercent());
        
        // CPU time
        $this->assertEquals(0, $stats->getUserTime());
        $this->assertEquals(0, $stats->getNice());
        $this->assertEquals(0, $stats->getSystemTime());
        $this->assertEquals(0, $stats->getIdle());
        $this->assertEquals(0, $stats->getIoWait());
        $this->assertEquals(0, $stats->getIrq());
        $this->assertEquals(0, $stats->getSoftIrq());
        $this->assertEquals(0, $stats->getSteal());
        $this->assertEquals(0, $stats->getGuest());
        
        // CPU calculations
        $this->assertEquals(0, $stats->getTotalTime());
        $this->assertEquals(0, $stats->getActiveTime());
        $this->assertEquals(0.0, $stats->getCpuUsagePercent());
    }

    public function testErrorCode(): void
    {
        $errorData = array_merge($this->testData, ['code' => 1]);
        $errorStats = new SystemStats($errorData);
        
        $this->assertEquals(1, $errorStats->getCode());
        $this->assertFalse($errorStats->isOk());
    }
} 