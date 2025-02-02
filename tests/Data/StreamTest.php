<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\Stream;

class StreamTest extends TestCase
{
    private array $testData = [
        'id' => 'test-stream-id',
        'name' => 'test_stream',
        'vhost' => 'test.local',
        'app' => 'live',
        'live_ms' => 60000,
        'clients' => 5,
        'frames' => 1800,
        'send_bytes' => 1048576,
        'recv_bytes' => 2097152,
        'kbps' => [
            'recv_30s' => 2048,
            'send_30s' => 1024
        ],
        'publish' => [
            'active' => true,
            'cid' => 'publisher-1'
        ],
        'video' => [
            'codec' => 'H264',
            'profile' => 'High',
            'level' => '4.1',
            'width' => 1920,
            'height' => 1080,
            'fps' => 30.0
        ],
        'audio' => [
            'codec' => 'AAC',
            'sample_rate' => 44100,
            'channels' => 2,
            'profile' => 'LC'
        ]
    ];

    private Stream $stream;

    protected function setUp(): void
    {
        $this->stream = new Stream($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['id'], $this->stream->getId());
        $this->assertEquals($this->testData['name'], $this->stream->getName());
        $this->assertEquals($this->testData['vhost'], $this->stream->getVhost());
        $this->assertEquals($this->testData['app'], $this->stream->getApp());
        $this->assertEquals($this->testData['clients'], $this->stream->getClients());
        $this->assertEquals($this->testData['frames'], $this->stream->getFrames());
    }

    public function testPublishInfo(): void
    {
        $this->assertTrue($this->stream->isActive());
        $this->assertEquals($this->testData['publish']['cid'], $this->stream->getPublisherId());

        // Test inactive stream
        $inactiveData = array_merge($this->testData, ['publish' => ['active' => false]]);
        $inactiveStream = new Stream($inactiveData);
        $this->assertFalse($inactiveStream->isActive());
    }

    public function testVideoInfo(): void
    {
        $this->assertEquals($this->testData['video']['codec'], $this->stream->getVideoCodec());
        $this->assertEquals($this->testData['video']['profile'], $this->stream->getVideoProfile());
        $this->assertEquals($this->testData['video']['level'], $this->stream->getVideoLevel());
        $this->assertEquals($this->testData['video']['width'], $this->stream->getVideoWidth());
        $this->assertEquals($this->testData['video']['height'], $this->stream->getVideoHeight());
        $this->assertEquals($this->testData['video']['fps'], $this->stream->getVideoFps());
    }

    public function testAudioInfo(): void
    {
        $this->assertEquals($this->testData['audio']['codec'], $this->stream->getAudioCodec());
        $this->assertEquals($this->testData['audio']['sample_rate'], $this->stream->getAudioSampleRate());
        $this->assertEquals($this->testData['audio']['channels'], $this->stream->getAudioChannels());
        $this->assertEquals($this->testData['audio']['profile'], $this->stream->getAudioProfile());
    }

    public function testBitrateInfo(): void
    {
        $this->assertEquals($this->testData['send_bytes'], $this->stream->getSendBytes());
        $this->assertEquals($this->testData['recv_bytes'], $this->stream->getRecvBytes());
        $this->assertEquals(1.0, $this->stream->getSendBitrateMbps());
        $this->assertEquals(2.0, $this->stream->getRecvBitrateMbps());
        $this->assertEquals(3.0, $this->stream->getTotalBitrateMbps());
    }

    public function testEmptyData(): void
    {
        $stream = new Stream([]);
        
        $this->assertEquals('', $stream->getId());
        $this->assertEquals('', $stream->getName());
        $this->assertEquals('', $stream->getVhost());
        $this->assertEquals('', $stream->getApp());
        $this->assertEquals(0, $stream->getClients());
        $this->assertEquals(0, $stream->getFrames());
        $this->assertFalse($stream->isActive());
        $this->assertEquals('', $stream->getPublisherId());
        
        // Check video information
        $this->assertEquals('', $stream->getVideoCodec());
        $this->assertEquals('', $stream->getVideoProfile());
        $this->assertEquals('', $stream->getVideoLevel());
        $this->assertEquals(0, $stream->getVideoWidth());
        $this->assertEquals(0, $stream->getVideoHeight());
        $this->assertEquals(0.0, $stream->getVideoFps());
        
        // Check audio information
        $this->assertEquals('', $stream->getAudioCodec());
        $this->assertEquals(0, $stream->getAudioSampleRate());
        $this->assertEquals(0, $stream->getAudioChannels());
        $this->assertEquals('', $stream->getAudioProfile());
        
        // Check bitrate
        $this->assertEquals(0, $stream->getSendBytes());
        $this->assertEquals(0, $stream->getRecvBytes());
        $this->assertEquals(0.0, $stream->getSendBitrateMbps());
        $this->assertEquals(0.0, $stream->getRecvBitrateMbps());
        $this->assertEquals(0.0, $stream->getTotalBitrateMbps());
    }

    public function testStreamStatus(): void
    {
        // Test inactive stream
        $inactiveStream = new Stream(array_merge($this->testData, [
            'publish' => ['active' => false]
        ]));
        $this->assertFalse($inactiveStream->isActive());
    }

    public function testStreamInfo(): void
    {
        // Check video information
        $this->assertEquals($this->testData['video']['codec'], $this->stream->getVideoCodec());
        $this->assertEquals($this->testData['video']['profile'], $this->stream->getVideoProfile());
        $this->assertEquals($this->testData['video']['level'], $this->stream->getVideoLevel());
        $this->assertEquals($this->testData['video']['width'], $this->stream->getVideoWidth());
        $this->assertEquals($this->testData['video']['height'], $this->stream->getVideoHeight());
        
        // Check audio information
        $this->assertEquals($this->testData['audio']['codec'], $this->stream->getAudioCodec());
        $this->assertEquals($this->testData['audio']['sample_rate'], $this->stream->getAudioSampleRate());
        $this->assertEquals($this->testData['audio']['channels'], $this->stream->getAudioChannels());
        $this->assertEquals($this->testData['audio']['profile'], $this->stream->getAudioProfile());
        
        // Check bitrate
        $this->assertEquals($this->testData['kbps']['recv_30s'] + $this->testData['kbps']['send_30s'], $this->stream->getBitrateKbps());
    }
} 