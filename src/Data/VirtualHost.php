<?php

namespace SrsClient\Data;

class VirtualHost
{
    private string $id;
    private string $name;
    private bool $enabled;
    private int $clients;
    private int $streams;
    private int $recvBytes;
    private int $sendBytes;
    private array $kbps;
    private array $hls;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->enabled = $data['enabled'] ?? false;
        $this->clients = $data['clients'] ?? 0;
        $this->streams = $data['streams'] ?? 0;
        $this->recvBytes = $data['recv_bytes'] ?? 0;
        $this->sendBytes = $data['send_bytes'] ?? 0;
        $this->kbps = $data['kbps'] ?? [];
        $this->hls = $data['hls'] ?? [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getClients(): int
    {
        return $this->clients;
    }

    public function getStreams(): int
    {
        return $this->streams;
    }

    public function getRecvBytes(): int
    {
        return $this->recvBytes;
    }

    public function getSendBytes(): int
    {
        return $this->sendBytes;
    }

    public function getRecvKbps(): int
    {
        return $this->kbps['recv_30s'] ?? 0;
    }

    public function getSendKbps(): int
    {
        return $this->kbps['send_30s'] ?? 0;
    }

    public function isHlsEnabled(): bool
    {
        return $this->hls['enabled'] ?? false;
    }

    public function getHlsFragment(): float
    {
        return $this->hls['fragment'] ?? 0.0;
    }

    /**
     * Get total bandwidth in Kbps
     */
    public function getTotalKbps(): int
    {
        return $this->getRecvKbps() + $this->getSendKbps();
    }

    /**
     * Get total transferred data in bytes
     */
    public function getTotalBytes(): int
    {
        return $this->getRecvBytes() + $this->getSendBytes();
    }
} 