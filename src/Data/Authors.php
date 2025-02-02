<?php

namespace SrsClient\Data;

class Authors
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

    public function getAuthors(): array
    {
        return $this->data['authors'] ?? [];
    }

    public function getLicense(): string
    {
        return $this->data['license'] ?? '';
    }

    public function getContributorsUrl(): string
    {
        return $this->data['contributors'] ?? '';
    }
} 