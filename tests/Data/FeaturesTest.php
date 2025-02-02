<?php

namespace SrsClient\Tests\Data;

use PHPUnit\Framework\TestCase;
use SrsClient\Data\Features;

class FeaturesTest extends TestCase
{
    private array $testData = [
        'code' => 0,
        'server' => 'test-server',
        'service' => 'test-service',
        'pid' => '12345',
        'data' => [
            'build' => [
                'date' => '2024-03-14',
                'mode' => 'release',
                'version' => '1.0.0'
            ],
            'build2' => 'v1.0.0',
            'options' => '--with-ssl --with-hls',
            'options2' => 'Additional options',
            'features' => [
                'ssl' => true,
                'hls' => true,
                'hds' => false,
                'callback' => true,
                'api' => true,
                'httpd' => true,
                'dvr' => false,
                'transcode' => true,
                'ingest' => false,
                'stat' => true,
                'caster' => false,
                'complex_send' => true,
                'tcp_nodelay' => true,
                'so_sendbuf' => false,
                'mr' => true
            ]
        ]
    ];

    private Features $features;

    protected function setUp(): void
    {
        $this->features = new Features($this->testData);
    }

    public function testBasicInfo(): void
    {
        $this->assertEquals($this->testData['code'], $this->features->getCode());
        $this->assertEquals($this->testData['server'], $this->features->getServer());
        $this->assertEquals($this->testData['service'], $this->features->getService());
        $this->assertEquals($this->testData['pid'], $this->features->getPid());
    }

    public function testBuildInfo(): void
    {
        $this->assertEquals('2024-03-14', $this->features->getBuildDate());
        $this->assertEquals('release', $this->features->getBuildMode());
        $this->assertEquals('1.0.0', $this->features->getBuildVersion());
        $this->assertEquals('v1.0.0', $this->features->getBuild2());
    }

    public function testOptions(): void
    {
        $this->assertEquals('--with-ssl --with-hls', $this->features->getOptions());
        $this->assertEquals('Additional options', $this->features->getOptions2());
    }

    public function testFeaturesList(): void
    {
        $expectedFeatures = $this->testData['data']['features'];
        $actualFeatures = $this->features->getFeatures();
        
        $this->assertIsArray($actualFeatures);
        $this->assertEquals($expectedFeatures, $actualFeatures);
    }

    public function testEnabledFeatures(): void
    {
        // Check enabled features
        $this->assertTrue($this->features->isSslEnabled());
        $this->assertTrue($this->features->isHlsEnabled());
        $this->assertFalse($this->features->isHdsEnabled());
        $this->assertTrue($this->features->isCallbackEnabled());
        $this->assertTrue($this->features->isApiEnabled());
        $this->assertTrue($this->features->isHttpdEnabled());
        $this->assertFalse($this->features->isDvrEnabled());
        $this->assertTrue($this->features->isTranscodeEnabled());
        $this->assertFalse($this->features->isIngestEnabled());
        $this->assertTrue($this->features->isStatEnabled());
        $this->assertFalse($this->features->isCasterEnabled());
        $this->assertTrue($this->features->isComplexSendEnabled());
        $this->assertTrue($this->features->isTcpNodelayEnabled());
        $this->assertFalse($this->features->isSoSendbufEnabled());
        $this->assertTrue($this->features->isMrEnabled());
    }

    public function testDisabledFeatures(): void
    {
        // Check disabled features
        $disabledFeatures = new Features(array_merge($this->testData, [
            'data' => array_merge($this->testData['data'], [
                'features' => array_merge($this->testData['data']['features'], [
                    'ssl' => false,
                    'hls' => false,
                    'hds' => false
                ])
            ])
        ]));

        $this->assertFalse($disabledFeatures->isSslEnabled());
        $this->assertFalse($disabledFeatures->isHlsEnabled());
        $this->assertFalse($disabledFeatures->isHdsEnabled());
    }

    public function testEmptyData(): void
    {
        $features = new Features([]);
        
        // Basic information
        $this->assertEquals(0, $features->getCode());
        $this->assertEquals('', $features->getServer());
        $this->assertEquals('', $features->getService());
        $this->assertEquals('', $features->getPid());
        
        // Build information
        $this->assertEquals('', $features->getBuildDate());
        $this->assertEquals('', $features->getBuildMode());
        $this->assertEquals('', $features->getBuildVersion());
        $this->assertEquals('', $features->getBuild2());
        
        // Options
        $this->assertEquals('', $features->getOptions());
        $this->assertEquals('', $features->getOptions2());
        
        // Features
        $this->assertIsArray($features->getFeatures());
        
        // Check all features are false with empty data
        $this->assertFalse($features->isSslEnabled());
        $this->assertFalse($features->isHlsEnabled());
        $this->assertFalse($features->isHdsEnabled());
        $this->assertFalse($features->isCallbackEnabled());
        $this->assertFalse($features->isApiEnabled());
        $this->assertFalse($features->isHttpdEnabled());
        $this->assertFalse($features->isDvrEnabled());
        $this->assertFalse($features->isTranscodeEnabled());
        $this->assertFalse($features->isIngestEnabled());
        $this->assertFalse($features->isStatEnabled());
        $this->assertFalse($features->isCasterEnabled());
        $this->assertFalse($features->isComplexSendEnabled());
        $this->assertFalse($features->isTcpNodelayEnabled());
        $this->assertFalse($features->isSoSendbufEnabled());
        $this->assertFalse($features->isMrEnabled());
    }

    public function testFeatureExists(): void
    {
        // Check existing feature
        $this->assertTrue($this->features->hasFeature('ssl'));
        
        // Check non-existing feature
        $this->assertFalse($this->features->hasFeature('non_existent_feature'));
    }
} 