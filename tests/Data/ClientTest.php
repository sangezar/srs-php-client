<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\Client;

class ClientTest extends TestCase
{
    private array $testData = [
        'id' => 'test-client-id',
        'vhost' => 'test.local',
        'stream' => 'test-stream',
        'ip' => '127.0.0.1',
        'pageUrl' => 'http://test.local/player',
        'swfUrl' => 'http://test.local/player.swf',
        'tcUrl' => 'rtmp://test.local/live',
        'url' => 'rtmp://test.local/live/stream',
        'type' => 'fmle-publish',
        'publish' => true,
        'kbps' => [
            'send_bytes' => 1024,
            'recv_bytes' => 2048,
            'send_30s' => 2048, // 2 Mbps
            'recv_30s' => 1024  // 1 Mbps
        ],
        'alive' => 123.45
    ];

    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client($this->testData);
    }

    public function testBasicGetters(): void
    {
        $this->assertEquals($this->testData['id'], $this->client->getId());
        $this->assertEquals($this->testData['vhost'], $this->client->getVhost());
        $this->assertEquals($this->testData['stream'], $this->client->getStream());
        $this->assertEquals($this->testData['ip'], $this->client->getIp());
        $this->assertEquals($this->testData['pageUrl'], $this->client->getPageUrl());
        $this->assertEquals($this->testData['swfUrl'], $this->client->getSwfUrl());
        $this->assertEquals($this->testData['tcUrl'], $this->client->getTcUrl());
        $this->assertEquals($this->testData['url'], $this->client->getUrl());
        $this->assertEquals($this->testData['type'], $this->client->getType());
    }

    public function testClientType(): void
    {
        $this->assertTrue($this->client->isPublisher());
        $this->assertFalse($this->client->isPlayer());
        $this->assertFalse($this->client->isHlsPlayer());

        // Test HLS player
        $hlsClient = new Client(array_merge($this->testData, ['type' => 'hls-player']));
        $this->assertTrue($hlsClient->isHlsPlayer());
        $this->assertTrue($hlsClient->isPlayer());
        $this->assertFalse($hlsClient->isPublisher());
    }

    public function testBitrateCalculations(): void
    {
        $this->assertEquals(1024, $this->client->getSendBytes());
        $this->assertEquals(2048, $this->client->getRecvBytes());
        $this->assertEquals(2.0, $this->client->getSendBitrateMbps());
        $this->assertEquals(1.0, $this->client->getRecvBitrateMbps());
        $this->assertEquals(3.0, $this->client->getTotalBitrateMbps());
    }

    public function testAliveDuration(): void
    {
        $this->assertEquals(123.45, $this->client->getAliveDuration());
    }

    public function testEmptyData(): void
    {
        $client = new Client([]);
        
        $this->assertEquals('', $client->getId());
        $this->assertEquals('', $client->getVhost());
        $this->assertEquals('', $client->getStream());
        $this->assertEquals('', $client->getIp());
        $this->assertEquals('', $client->getPageUrl());
        $this->assertEquals('', $client->getSwfUrl());
        $this->assertEquals('', $client->getTcUrl());
        $this->assertEquals('', $client->getUrl());
        $this->assertEquals('', $client->getType());
        $this->assertEquals(0, $client->getSendBytes());
        $this->assertEquals(0, $client->getRecvBytes());
        $this->assertEquals(0.0, $client->getSendBitrateMbps());
        $this->assertEquals(0.0, $client->getRecvBitrateMbps());
        $this->assertEquals(0.0, $client->getTotalBitrateMbps());
        $this->assertEquals(0.0, $client->getAliveDuration());
    }
} 