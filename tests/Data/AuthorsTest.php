<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\Authors;

class AuthorsTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'authors' => [
                'John Doe',
                'Jane Smith',
                'Bob Johnson'
            ],
            'license' => 'MIT',
            'contributors' => 'https://github.com/example/repo/contributors'
        ]
    ];

    private Authors $authors;

    protected function setUp(): void
    {
        $this->authors = new Authors($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['code'], $this->authors->getCode());
        $this->assertEquals($this->testData['server'], $this->authors->getServer());
        $this->assertEquals($this->testData['service'], $this->authors->getService());
        $this->assertEquals($this->testData['pid'], $this->authors->getPid());
    }

    public function testAuthors(): void
    {
        $expectedAuthors = $this->testData['data']['authors'];
        $actualAuthors = $this->authors->getAuthors();
        
        $this->assertIsArray($actualAuthors);
        $this->assertCount(3, $actualAuthors);
        $this->assertEquals($expectedAuthors, $actualAuthors);
    }

    public function testLicense(): void
    {
        $this->assertEquals('MIT', $this->authors->getLicense());
    }

    public function testContributorsUrl(): void
    {
        $expectedUrl = 'https://github.com/example/repo/contributors';
        $this->assertEquals($expectedUrl, $this->authors->getContributorsUrl());
    }

    public function testEmptyData(): void
    {
        $authors = new Authors([]);
        
        // Basic information
        $this->assertEquals(0, $authors->getCode());
        $this->assertEquals('', $authors->getServer());
        $this->assertEquals('', $authors->getService());
        $this->assertEquals('', $authors->getPid());
        
        // Specific data
        $this->assertEquals('', $authors->getLicense());
        $this->assertEquals('', $authors->getContributorsUrl());
    }
} 