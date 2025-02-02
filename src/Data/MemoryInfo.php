<?php

namespace SrsClient\Data;

class MemoryInfo
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

    public function getSystemMemoryKb(): array
    {
        return $this->data['system_mem_kb'] ?? [];
    }

    public function getMemoryKb(): array
    {
        return $this->data['memory_kb'] ?? [];
    }

    public function getTotalMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['total'] ?? 0;
    }

    public function getFreeMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['free'] ?? 0;
    }

    public function getSharedMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['shared'] ?? 0;
    }

    public function getBuffersMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['buffers'] ?? 0;
    }

    public function getCachedMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['cached'] ?? 0;
    }

    public function getActualUsedMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['actual_used'] ?? 0;
    }

    public function getActualFreeMemoryKb(): int
    {
        return $this->getSystemMemoryKb()['actual_free'] ?? 0;
    }

    public function getMemoryUsagePercent(): float
    {
        $total = $this->getTotalMemoryKb();
        if ($total === 0) {
            return 0.0;
        }
        return round(($this->getActualUsedMemoryKb() / $total) * 100, 2);
    }

    public function getMemoryFreePercent(): float
    {
        return 100 - $this->getMemoryUsagePercent();
    }

    public function getRssKb(): int
    {
        return $this->getMemoryKb()['rss'] ?? 0;
    }

    public function getSharedKb(): int
    {
        return $this->getMemoryKb()['shared'] ?? 0;
    }

    public function getPrivateKb(): int
    {
        return $this->getMemoryKb()['private'] ?? 0;
    }

    public function isOk(): bool
    {
        return $this->code === 0;
    }

    public function getMemTotal(): int
    {
        return $this->getTotalMemoryKb();
    }

    public function getMemFree(): int
    {
        return $this->getFreeMemoryKb();
    }

    public function getMemActive(): int
    {
        return $this->getSystemMemoryKb()['active'] ?? 0;
    }

    public function getRealInUse(): int
    {
        return $this->getActualUsedMemoryKb();
    }

    public function getNotInUse(): int
    {
        return $this->getActualFreeMemoryKb();
    }

    public function getBuffers(): int
    {
        return $this->getBuffersMemoryKb();
    }

    public function getCached(): int
    {
        return $this->getCachedMemoryKb();
    }

    public function getRealMemoryUsage(): int
    {
        return $this->getRealInUse();
    }

    public function getSwapTotal(): int
    {
        return $this->getSystemMemoryKb()['swap_total'] ?? 0;
    }

    public function getSwapFree(): int
    {
        return $this->getSystemMemoryKb()['swap_free'] ?? 0;
    }

    public function getSwapUsagePercent(): float
    {
        $total = $this->getSwapTotal();
        if ($total === 0) {
            return 0.0;
        }
        return round((($total - $this->getSwapFree()) / $total) * 100, 2);
    }

    public function getPercentRam(): float
    {
        return $this->getMemoryUsagePercent();
    }

    public function getPercentSwap(): float
    {
        return $this->getSwapUsagePercent();
    }
} 
