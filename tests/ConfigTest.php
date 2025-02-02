<?php

namespace SrsClient\Tests;

use PHPUnit\Framework\TestCase;
use SrsClient\Config;

class ConfigTest extends TestCase
{
    public function testCreateConfig(): void
    {
        $baseUrl = 'http://localhost:1985';
        $config = new Config($baseUrl);
        
        $this->assertEquals($baseUrl, $config->getBaseUrl());
        $this->assertNull($config->getCredentials());
        $this->assertEquals(30, $config->getTimeout());
        $this->assertTrue($config->getVerify());
        $this->assertFalse($config->getDebug());
        $this->assertEmpty($config->getHeaders());
    }

    public function testCreateConfigWithOptions(): void
    {
        $baseUrl = 'http://localhost:1985';
        $options = [
            'credentials' => [
                'username' => 'admin',
                'password' => 'password'
            ],
            'timeout' => 5,
            'verify' => false,
            'debug' => true,
            'headers' => [
                'User-Agent' => 'Test-App/1.0'
            ]
        ];
        
        $config = new Config($baseUrl, $options);
        
        $this->assertEquals($baseUrl, $config->getBaseUrl());
        $this->assertEquals($options['credentials'], $config->getCredentials());
        $this->assertEquals($options['timeout'], $config->getTimeout());
        $this->assertEquals($options['verify'], $config->getVerify());
        $this->assertEquals($options['debug'], $config->getDebug());
        $this->assertEquals($options['headers'], $config->getHeaders());
    }

    public function testCreateConfigWithProxy(): void
    {
        $baseUrl = 'http://localhost:1985';
        $options = [
            'proxy' => 'http://proxy:8080'
        ];
        
        $config = new Config($baseUrl, $options);
        
        $this->assertEquals($options['proxy'], $config->getProxy());
    }

    public function testInvalidBaseUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Config('invalid-url');
    }

    public function testInvalidTimeout(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Config('http://localhost:1985', ['timeout' => -1]);
    }

    public function testInvalidCredentials(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Config('http://localhost:1985', [
            'credentials' => ['username' => 'admin'] // missing password
        ]);
    }
} 