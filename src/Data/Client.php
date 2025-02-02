<?php

namespace SrsClient\Data;

class Client
{
    private string $id;
    private string $vhost;
    private string $stream;
    private string $ip;
    private string $pageUrl;
    private string $swfUrl;
    private string $tcUrl;
    private string $url;
    private string $type;
    private bool $publish;
    private array $kbps;
    private float $alive;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->vhost = $data['vhost'] ?? '';
        $this->stream = $data['stream'] ?? '';
        $this->ip = $data['ip'] ?? '';
        $this->pageUrl = $data['pageUrl'] ?? '';
        $this->swfUrl = $data['swfUrl'] ?? '';
        $this->tcUrl = $data['tcUrl'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->publish = $data['publish'] ?? false;
        $this->kbps = $data['kbps'] ?? [];
        $this->alive = $data['alive'] ?? 0.0;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVhost(): string
    {
        return $this->vhost;
    }

    public function getStream(): string
    {
        return $this->stream;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPageUrl(): string
    {
        return $this->pageUrl;
    }

    public function getSwfUrl(): string
    {
        return $this->swfUrl;
    }

    public function getTcUrl(): string
    {
        return $this->tcUrl;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isPublisher(): bool
    {
        return $this->type === 'fmle-publish';
    }

    public function isPlayer(): bool
    {
        return !$this->isPublisher();
    }

    public function isHlsPlayer(): bool
    {
        return $this->type === 'hls-player';
    }

    public function getAliveDuration(): float
    {
        return $this->alive;
    }

    public function getSendBytes(): int
    {
        return $this->kbps['send_bytes'] ?? 0;
    }

    public function getRecvBytes(): int
    {
        return $this->kbps['recv_bytes'] ?? 0;
    }

    public function getSendBitrateMbps(): float
    {
        $kbps = $this->kbps['send_30s'] ?? 0;
        return $kbps / 1024;
    }

    public function getRecvBitrateMbps(): float
    {
        $kbps = $this->kbps['recv_30s'] ?? 0;
        return $kbps / 1024;
    }

    public function getTotalBitrateMbps(): float
    {
        return $this->getSendBitrateMbps() + $this->getRecvBitrateMbps();
    }
} 