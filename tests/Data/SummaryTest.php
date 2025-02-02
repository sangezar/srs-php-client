<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\Summary;

class SummaryTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'ok' => true,
            'now_ms' => 1615478400000,
            'self' => [
                'version' => '4.0.0',
                'ppid' => 1,
                'mem_kbyte' => 102400,
                'mem_percent' => 5.5,
                'srs_uptime' => 86400,
                'mem_ram_kbyte' => 8388608,
                'mem_ram_percent' => 75.5,
                'mem_swap_kbyte' => 4194304,
                'mem_swap_percent' => 25.5
            ],
            'system' => [
                'uptime' => 604800.0,
                'idle_time' => 302400.0,
                'load_1m' => 1.5,
                'load_5m' => 1.2,
                'load_15m' => 1.0,
                'net_recv_bytes' => 1024000,
                'net_send_bytes' => 2048000,
                'net_recvi_bytes' => 512000,
                'net_sendi_bytes' => 256000
            ],
            'argv' => '--config server.conf',
            'cwd' => '/usr/local/srs',
            'cpu_percent' => 25.5,
            'system_cpu_percent' => 45.5,
            'disk_read_KBps' => 1024,
            'disk_write_KBps' => 512,
            'disk_busy_percent' => 35.5,
            'cpus' => 8,
            'cpus_online' => 8,
            'srs_recv_bytes' => 4096000,
            'srs_send_bytes' => 8192000,
            'conn_sys' => 1000,
            'conn_sys_et' => 800,
            'conn_sys_tw' => 100,
            'conn_sys_udp' => 50,
            'conn_srs' => 500
        ]
    ];

    private Summary $summary;

    protected function setUp(): void
    {
        $this->summary = new Summary($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertTrue($this->summary->isOk());
        $this->assertEquals(1615478400000, $this->summary->getNowMs());
    }

    public function testSelfInfo(): void
    {
        $this->assertEquals('4.0.0', $this->summary->getVersion());
        $this->assertEquals('12345', $this->summary->getPid());
        $this->assertEquals(1, $this->summary->getPpid());
        $this->assertEquals('--config server.conf', $this->summary->getArgv());
        $this->assertEquals('/usr/local/srs', $this->summary->getCwd());
        $this->assertEquals(102400, $this->summary->getMemoryKbyte());
        $this->assertEquals(5.5, $this->summary->getMemoryPercent());
        $this->assertEquals(25.5, $this->summary->getCpuPercent());
        $this->assertEquals(86400, $this->summary->getSrsUptime());
        $this->assertEquals('1 days, 00:00:00', $this->summary->getUptimeDuration());
    }

    public function testSystemInfo(): void
    {
        $this->assertEquals(45.5, $this->summary->getSystemCpuPercent());
        $this->assertEquals(1024, $this->summary->getDiskReadKBps());
        $this->assertEquals(512, $this->summary->getDiskWriteKBps());
        $this->assertEquals(35.5, $this->summary->getDiskBusyPercent());
        $this->assertEquals(8388608, $this->summary->getMemRamKbyte());
        $this->assertEquals(75.5, $this->summary->getMemRamPercent());
        $this->assertEquals(4194304, $this->summary->getMemSwapKbyte());
        $this->assertEquals(25.5, $this->summary->getMemSwapPercent());
        $this->assertEquals(8, $this->summary->getCpuCount());
        $this->assertEquals(8, $this->summary->getCpusOnline());
    }

    public function testSystemMetrics(): void
    {
        $this->assertEquals(604800.0, $this->summary->getUptime());
        $this->assertEquals(302400.0, $this->summary->getIdleTime());
        $this->assertEquals(1.5, $this->summary->getLoad1m());
        $this->assertEquals(1.2, $this->summary->getLoad5m());
        $this->assertEquals(1.0, $this->summary->getLoad15m());
    }

    public function testNetworkStats(): void
    {
        $this->assertEquals(1024000, $this->summary->getNetworkRecvBytes());
        $this->assertEquals(2048000, $this->summary->getNetworkSendBytes());
        $this->assertEquals(512000, $this->summary->getNetworkReceivedBytes());
        $this->assertEquals(256000, $this->summary->getNetworkSentBytes());
    }

    public function testSrsStats(): void
    {
        $this->assertEquals(4096000, $this->summary->getSrsRecvBytes());
        $this->assertEquals(8192000, $this->summary->getSrsSendBytes());
    }

    public function testConnectionStats(): void
    {
        $this->assertEquals(1000, $this->summary->getConnectionsSys());
        $this->assertEquals(800, $this->summary->getConnectionsSysEt());
        $this->assertEquals(100, $this->summary->getConnectionsSysTw());
        $this->assertEquals(50, $this->summary->getConnectionsSysUdp());
        $this->assertEquals(500, $this->summary->getConnectionsSrs());
    }

    public function testFormattedOutput(): void
    {
        $expectedMemoryUsage = '100.00 MB (5.50%)';
        $expectedSystemMemory = 'RAM: 8.00 GB (75.50%), Swap: 4.00 GB (25.50%)';
        
        $this->assertEquals($expectedMemoryUsage, $this->summary->getMemoryUsageFormatted());
        $this->assertEquals($expectedSystemMemory, $this->summary->getSystemMemoryFormatted());
    }

    public function testEmptyData(): void
    {
        $summary = new Summary([]);
        
        // Basic information
        $this->assertEquals(0, $summary->getCode());
        $this->assertEquals('', $summary->getServer());
        $this->assertEquals('', $summary->getService());
        $this->assertEquals('', $summary->getPid());
        $this->assertTrue($summary->isOk());
    }
} 