<?php

namespace SrsClient\Data;

class Stream
{
    private string $id;
    private string $name;
    private string $vhost;
    private string $app;
    private string $tcUrl;
    private string $url;
    private int $liveMs;
    private int $clients;
    private int $frames;
    private int $sendBytes;
    private int $recvBytes;
    private array $kbps;
    private array $publish;
    private array $video;
    private array $audio;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->vhost = $data['vhost'] ?? '';
        $this->app = $data['app'] ?? '';
        $this->tcUrl = $data['tcUrl'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->liveMs = $data['live_ms'] ?? 0;
        $this->clients = $data['clients'] ?? 0;
        $this->frames = $data['frames'] ?? 0;
        $this->sendBytes = $data['send_bytes'] ?? 0;
        $this->recvBytes = $data['recv_bytes'] ?? 0;
        $this->kbps = $data['kbps'] ?? [];
        $this->publish = $data['publish'] ?? [];
        $this->video = $data['video'] ?? [];
        $this->audio = $data['audio'] ?? [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVhost(): string
    {
        return $this->vhost;
    }

    public function getApp(): string
    {
        return $this->app;
    }

    public function getTcUrl(): string
    {
        return $this->tcUrl;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLiveMs(): int
    {
        return $this->liveMs;
    }

    public function getClients(): int
    {
        return $this->clients;
    }

    public function getFrames(): int
    {
        return $this->frames;
    }

    public function getSendBytes(): int
    {
        return $this->sendBytes;
    }

    public function getRecvBytes(): int
    {
        return $this->recvBytes;
    }

    public function getRecvKbps(): int
    {
        return $this->kbps['recv_30s'] ?? 0;
    }

    public function getSendKbps(): int
    {
        return $this->kbps['send_30s'] ?? 0;
    }

    public function isActive(): bool
    {
        return ($this->publish['active'] ?? false) === true;
    }

    public function getPublishClientId(): ?string
    {
        return $this->publish['cid'] ?? null;
    }

    // Video methods
    public function getVideoCodec(): string
    {
        return $this->video['codec'] ?? '';
    }

    public function getVideoProfile(): string
    {
        return $this->video['profile'] ?? '';
    }

    public function getVideoLevel(): string
    {
        return $this->video['level'] ?? '';
    }

    public function getVideoWidth(): int
    {
        return $this->video['width'] ?? 0;
    }

    public function getVideoHeight(): int
    {
        return $this->video['height'] ?? 0;
    }

    // Audio methods
    public function getAudioCodec(): string
    {
        return $this->audio['codec'] ?? '';
    }

    public function getAudioSampleRate(): int
    {
        return $this->audio['sample_rate'] ?? 0;
    }

    public function getAudioChannel(): int
    {
        return $this->audio['channel'] ?? 0;
    }

    public function getAudioProfile(): string
    {
        return $this->audio['profile'] ?? '';
    }

    // Helper methods
    public function getBitrateKbps(): int
    {
        return $this->getRecvKbps() + $this->getSendKbps();
    }

    public function getDurationInSeconds(): float
    {
        return $this->getLiveMs() / 1000;
    }

    public function getPublisherId(): string
    {
        return $this->publish['cid'] ?? '';
    }

    public function getVideoFps(): float
    {
        return $this->video['fps'] ?? 0.0;
    }

    public function getAudioChannels(): int
    {
        return $this->audio['channels'] ?? 0;
    }

    public function getSendBitrateMbps(): float
    {
        return $this->getSendKbps() / 1024.0;
    }

    public function getRecvBitrateMbps(): float
    {
        return $this->getRecvKbps() / 1024.0;
    }

    public function getTotalBitrateMbps(): float
    {
        return $this->getSendBitrateMbps() + $this->getRecvBitrateMbps();
    }
} 