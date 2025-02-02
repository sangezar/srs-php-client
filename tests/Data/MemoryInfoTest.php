<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\MemoryInfo;

class MemoryInfoTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'system_mem_kb' => [
                'total' => 16777216,        // 16GB
                'free' => 8388608,          // 8GB
                'shared' => 1048576,        // 1GB
                'buffers' => 524288,        // 512MB
                'cached' => 4194304,        // 4GB
                'actual_used' => 3670016,   // ~3.5GB
                'actual_free' => 13107200,  // ~12.5GB
                'active' => 3145728,        // 3GB
                'swap_total' => 8388608,    // 8GB
                'swap_free' => 6291456      // 6GB
            ],
            'memory_kb' => [
                'rss' => 204800,            // 200MB
                'shared' => 102400,         // 100MB
                'private' => 102400         // 100MB
            ]
        ]
    ];

    private MemoryInfo $memInfo;

    protected function setUp(): void
    {
        $this->memInfo = new MemoryInfo($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['code'], $this->memInfo->getCode());
        $this->assertEquals($this->testData['server'], $this->memInfo->getServer());
        $this->assertEquals($this->testData['service'], $this->memInfo->getService());
        $this->assertEquals($this->testData['pid'], $this->memInfo->getPid());
        $this->assertTrue($this->memInfo->isOk());
    }

    public function testSystemMemory(): void
    {
        $this->assertEquals(16777216, $this->memInfo->getTotalMemoryKb());
        $this->assertEquals(8388608, $this->memInfo->getFreeMemoryKb());
        $this->assertEquals(1048576, $this->memInfo->getSharedMemoryKb());
        $this->assertEquals(524288, $this->memInfo->getBuffersMemoryKb());
        $this->assertEquals(4194304, $this->memInfo->getCachedMemoryKb());
        $this->assertEquals(3670016, $this->memInfo->getActualUsedMemoryKb());
        $this->assertEquals(13107200, $this->memInfo->getActualFreeMemoryKb());
    }

    public function testMemoryPercentages(): void
    {
        // Actual used / Total = 3670016 / 16777216 ≈ 21.88%
        $this->assertEquals(21.88, $this->memInfo->getMemoryUsagePercent());
        // 100% - 21.88% = 78.12%
        $this->assertEquals(78.12, $this->memInfo->getMemoryFreePercent());
    }

    public function testProcessMemory(): void
    {
        $this->assertEquals(204800, $this->memInfo->getRssKb());
        $this->assertEquals(102400, $this->memInfo->getSharedKb());
        $this->assertEquals(102400, $this->memInfo->getPrivateKb());
    }

    public function testAliases(): void
    {
        $this->assertEquals($this->memInfo->getTotalMemoryKb(), $this->memInfo->getMemTotal());
        $this->assertEquals($this->memInfo->getFreeMemoryKb(), $this->memInfo->getMemFree());
        $this->assertEquals(3145728, $this->memInfo->getMemActive());
        $this->assertEquals($this->memInfo->getActualUsedMemoryKb(), $this->memInfo->getRealInUse());
        $this->assertEquals($this->memInfo->getActualFreeMemoryKb(), $this->memInfo->getNotInUse());
        $this->assertEquals($this->memInfo->getBuffersMemoryKb(), $this->memInfo->getBuffers());
        $this->assertEquals($this->memInfo->getCachedMemoryKb(), $this->memInfo->getCached());
        $this->assertEquals($this->memInfo->getRealInUse(), $this->memInfo->getRealMemoryUsage());
    }

    public function testSwapMemory(): void
    {
        $this->assertEquals(8388608, $this->memInfo->getSwapTotal());
        $this->assertEquals(6291456, $this->memInfo->getSwapFree());
        
        // (8388608 - 6291456) / 8388608 * 100 ≈ 25.00%
        $this->assertEquals(25.00, $this->memInfo->getSwapUsagePercent());
    }

    public function testPercentages(): void
    {
        $this->assertEquals($this->memInfo->getMemoryUsagePercent(), $this->memInfo->getPercentRam());
        $this->assertEquals($this->memInfo->getSwapUsagePercent(), $this->memInfo->getPercentSwap());
    }

    public function testEmptyData(): void
    {
        $meminfo = new MemoryInfo([]);
        
        // Basic information
        $this->assertEquals(0, $meminfo->getCode());
        $this->assertEquals('', $meminfo->getServer());
        $this->assertEquals('', $meminfo->getService());
        $this->assertEquals('', $meminfo->getPid());
        $this->assertTrue($meminfo->isOk());
        
        // System memory
        $this->assertEquals(0, $meminfo->getMemTotal());
        $this->assertEquals(0, $meminfo->getMemFree());
        $this->assertEquals(0, $meminfo->getMemActive());
        $this->assertEquals(0, $meminfo->getBuffers());
        $this->assertEquals(0, $meminfo->getCached());
        
        // Percentages
        $this->assertEquals(0.0, $meminfo->getMemoryUsagePercent());
        $this->assertEquals(0.0, $meminfo->getSwapUsagePercent());
        
        // Process memory
        $this->assertEquals(0, $meminfo->getRealInUse());
        $this->assertEquals(0, $meminfo->getNotInUse());
        $this->assertEquals(0, $meminfo->getRealMemoryUsage());
    }

    public function testErrorCode(): void
    {
        $errorData = array_merge($this->testData, ['code' => 1]);
        $errorMemInfo = new MemoryInfo($errorData);
        
        $this->assertEquals(1, $errorMemInfo->getCode());
        $this->assertFalse($errorMemInfo->isOk());
    }
} 