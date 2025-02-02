<?php

namespace SrsClient\Data;

class Features
{
    private int $code;
    private string $server;
    private string $service;
    private string $pid;
    private array $data;

    public function __construct(array $data)
    {
        $this->code = $data['code'] ?? 0;
        $this->server = $data['server'] ?? '';
        $this->service = $data['service'] ?? '';
        $this->pid = $data['pid'] ?? '';
        $this->data = $data['data'] ?? [];
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getPid(): string
    {
        return $this->pid;
    }

    public function getBuild(): array
    {
        return $this->data['build'] ?? [];
    }

    public function getBuild2(): string
    {
        return $this->data['build2'] ?? '';
    }

    public function getOptions(): string
    {
        return $this->data['options'] ?? '';
    }

    public function getOptions2(): string
    {
        return $this->data['options2'] ?? '';
    }

    public function getFeatures(): array
    {
        return $this->data['features'] ?? [];
    }

    public function hasFeature(string $feature): bool
    {
        $features = $this->getFeatures();
        return isset($features[$feature]) && $features[$feature] === true;
    }

    public function isSslEnabled(): bool
    {
        return $this->hasFeature('ssl');
    }

    public function isHlsEnabled(): bool
    {
        return $this->hasFeature('hls');
    }

    public function isHdsEnabled(): bool
    {
        return $this->hasFeature('hds');
    }

    public function isCallbackEnabled(): bool
    {
        return $this->hasFeature('callback');
    }

    public function isApiEnabled(): bool
    {
        return $this->hasFeature('api');
    }

    public function isHttpdEnabled(): bool
    {
        return $this->hasFeature('httpd');
    }

    public function isDvrEnabled(): bool
    {
        return $this->hasFeature('dvr');
    }

    public function isTranscodeEnabled(): bool
    {
        return $this->hasFeature('transcode');
    }

    public function isIngestEnabled(): bool
    {
        return $this->hasFeature('ingest');
    }

    public function isStatEnabled(): bool
    {
        return $this->hasFeature('stat');
    }

    public function isCasterEnabled(): bool
    {
        return $this->hasFeature('caster');
    }

    public function isComplexSendEnabled(): bool
    {
        return $this->hasFeature('complex_send');
    }

    public function isTcpNodelayEnabled(): bool
    {
        return $this->hasFeature('tcp_nodelay');
    }

    public function isSoSendbufEnabled(): bool
    {
        return $this->hasFeature('so_sendbuf');
    }

    public function isMrEnabled(): bool
    {
        return $this->hasFeature('mr');
    }

    public function getBuildDate(): string
    {
        return $this->getBuild()['date'] ?? '';
    }

    public function getBuildMode(): string
    {
        return $this->getBuild()['mode'] ?? '';
    }

    public function getBuildVersion(): string
    {
        return $this->getBuild()['version'] ?? '';
    }
} 