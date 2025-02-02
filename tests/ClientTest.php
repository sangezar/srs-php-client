<?php

namespace SrsClient\Tests;

use PHPUnit\Framework\TestCase;
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

class ClientTest extends TestCase
{
    private Client $client;
    private string $baseUrl;

    protected function setUp(): void
    {
        $this->baseUrl = getenv('SRS_HOST') ?: 'http://localhost:1985';
        $config = new Config($this->baseUrl, [
            'credentials' => [
                'username' => getenv('SRS_USERNAME'),
                'password' => getenv('SRS_PASSWORD')
            ]
        ]);
        $this->client = new Client($config);
    }

    public function testGetVersion(): void
    {
        try {
            $version = $this->client->getVersion();
        } catch (\Exception $e) {
            throw $e;
        }
        
        $this->assertNotNull($version);
        $this->assertIsString($version->getVersion());
        $this->assertIsString($version->getServerId());
        $this->assertIsString($version->getServiceId());
        $this->assertIsString($version->getPid());
    }

    public function testGetStreams(): void
    {
        $streams = $this->client->getStreams();
        
        $this->assertIsArray($streams);
        if (!empty($streams)) {
            $stream = $streams[0];
            $this->assertNotNull($stream->getId());
            $this->assertNotNull($stream->getName());
            $this->assertIsInt($stream->getClients());
        }
    }

    public function testGetVhosts(): void
    {
        $vhosts = $this->client->getVhosts();
        
        $this->assertIsArray($vhosts);
        if (!empty($vhosts)) {
            $vhost = $vhosts[0];
            $this->assertNotNull($vhost->getId());
            $this->assertNotNull($vhost->getName());
            $this->assertIsBool($vhost->isEnabled());
        }
    }

    public function testGetClients(): void
    {
        $clients = $this->client->getClients();
        
        $this->assertIsArray($clients);
        if (!empty($clients)) {
            $client = $clients[0];
            $this->assertNotNull($client->getId());
            $this->assertNotNull($client->getIp());
        }
    }

    public function testGetResourceUsage(): void
    {
        $usage = $this->client->getResourceUsage();
        
        $this->assertNotNull($usage);
        $this->assertIsFloat($usage->getUserTime());
        $this->assertIsFloat($usage->getSystemTime());
        $this->assertIsInt($usage->getMaxRss());
    }

    public function testInvalidEndpoint(): void
    {
        $this->expectException(SrsApiException::class);
        
        $config = new Config('http://invalid-host:1985');
        $client = new Client($config);
        $client->getVersion();
    }

    public function testDeleteNonExistentStream(): void
    {
        $this->expectException(SrsApiException::class);
        $this->expectExceptionCode(404);
        
        $this->client->deleteStream('non-existent-stream');
    }

    public function testDeleteNonExistentClient(): void
    {
        $this->expectException(SrsApiException::class);
        $this->expectExceptionCode(404);
        
        $this->client->deleteClient('non-existent-client');
    }
} 