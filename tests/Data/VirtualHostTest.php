<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\VirtualHost;

class VirtualHostTest extends TestCase
{
    private array $testData = [
        'id' => 'test-vhost-id',
        'name' => 'test.local',
        'enabled' => true,
        'clients' => 10,
        'streams' => 5,
        'recv_bytes' => 1048576, // 1 MB
        'send_bytes' => 2097152, // 2 MB
        'kbps' => [
            'recv_30s' => 1024, // 1 Mbps
            'send_30s' => 2048  // 2 Mbps
        ],
        'hls' => [
            'enabled' => true,
            'fragment' => 10.0
        ]
    ];

    private VirtualHost $vhost;

    protected function setUp(): void
    {
        $this->vhost = new VirtualHost($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['id'], $this->vhost->getId());
        $this->assertEquals($this->testData['name'], $this->vhost->getName());
        $this->assertTrue($this->vhost->isEnabled());
        $this->assertEquals($this->testData['clients'], $this->vhost->getClients());
        $this->assertEquals($this->testData['streams'], $this->vhost->getStreams());
    }

    public function testBandwidthInfo(): void
    {
        $this->assertEquals($this->testData['recv_bytes'], $this->vhost->getRecvBytes());
        $this->assertEquals($this->testData['send_bytes'], $this->vhost->getSendBytes());
        $this->assertEquals($this->testData['kbps']['recv_30s'], $this->vhost->getRecvKbps());
        $this->assertEquals($this->testData['kbps']['send_30s'], $this->vhost->getSendKbps());
        
        // Check total values
        $this->assertEquals(3072, $this->vhost->getTotalKbps());
        $this->assertEquals(3145728, $this->vhost->getTotalBytes());
    }

    public function testHlsConfig(): void
    {
        $this->assertTrue($this->vhost->isHlsEnabled());
        $this->assertEquals($this->testData['hls']['fragment'], $this->vhost->getHlsFragment());

        // Test vhost with disabled HLS
        $disabledHlsData = array_merge($this->testData, ['hls' => ['enabled' => false]]);
        $disabledHlsVhost = new VirtualHost($disabledHlsData);
        $this->assertFalse($disabledHlsVhost->isHlsEnabled());
    }

    public function testEmptyData(): void
    {
        $vhost = new VirtualHost([]);
        
        // Basic information
        $this->assertEquals('', $vhost->getId());
        $this->assertEquals('', $vhost->getName());
        $this->assertFalse($vhost->isEnabled());
        $this->assertEquals(0, $vhost->getClients());
        $this->assertEquals(0, $vhost->getStreams());
        
        // Traffic information
        $this->assertEquals(0, $vhost->getRecvBytes());
        $this->assertEquals(0, $vhost->getSendBytes());
        $this->assertEquals(0, $vhost->getRecvKbps());
        $this->assertEquals(0, $vhost->getSendKbps());
        $this->assertEquals(0, $vhost->getTotalKbps());
        $this->assertEquals(0, $vhost->getTotalBytes());
        
        // HLS configuration
        $this->assertFalse($vhost->isHlsEnabled());
        $this->assertEquals(0.0, $vhost->getHlsFragment());
    }

    public function testDisabledVhost(): void
    {
        $disabledData = array_merge($this->testData, ['enabled' => false]);
        $disabledVhost = new VirtualHost($disabledData);
        
        $this->assertFalse($disabledVhost->isEnabled());
        // Check that other data is still accessible
        $this->assertEquals($this->testData['id'], $disabledVhost->getId());
        $this->assertEquals($this->testData['clients'], $disabledVhost->getClients());
    }
} 