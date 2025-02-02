<?php

namespace SrsClient\Data;

class SystemStats
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

    public function getCpuInfo(): array
    {
        return $this->data['cpu'] ?? [];
    }

    public function getNetworkInfo(): array
    {
        return $this->data['network'] ?? [];
    }

    public function getDiskInfo(): array
    {
        return $this->data['disk'] ?? [];
    }

    public function getCpuPercent(): float
    {
        return $this->getCpuInfo()['percent'] ?? 0.0;
    }

    public function getCpuLoadAverage1(): float
    {
        return $this->getCpuInfo()['load_1'] ?? 0.0;
    }

    public function getCpuLoadAverage5(): float
    {
        return $this->getCpuInfo()['load_5'] ?? 0.0;
    }

    public function getCpuLoadAverage15(): float
    {
        return $this->getCpuInfo()['load_15'] ?? 0.0;
    }

    public function getNetworkSendBytesTotal(): int
    {
        return $this->getNetworkInfo()['bytes_send'] ?? 0;
    }

    public function getNetworkRecvBytesTotal(): int
    {
        return $this->getNetworkInfo()['bytes_recv'] ?? 0;
    }

    public function getNetworkSendBytesPerSecond(): int
    {
        return $this->getNetworkInfo()['bytes_send_delta'] ?? 0;
    }

    public function getNetworkRecvBytesPerSecond(): int
    {
        return $this->getNetworkInfo()['bytes_recv_delta'] ?? 0;
    }

    public function getDiskReadBytesTotal(): int
    {
        return $this->getDiskInfo()['read_bytes'] ?? 0;
    }

    public function getDiskWriteBytesTotal(): int
    {
        return $this->getDiskInfo()['write_bytes'] ?? 0;
    }

    public function getDiskReadBytesPerSecond(): int
    {
        return $this->getDiskInfo()['read_bytes_delta'] ?? 0;
    }

    public function getDiskWriteBytesPerSecond(): int
    {
        return $this->getDiskInfo()['write_bytes_delta'] ?? 0;
    }

    public function getDiskUsagePercent(): float
    {
        return $this->getDiskInfo()['busy'] ?? 0.0;
    }

    public function isOk(): bool
    {
        return $this->code === 0;
    }

    public function getUserTime(): int
    {
        return $this->getCpuInfo()['user'] ?? 0;
    }

    public function getNice(): int
    {
        return $this->getCpuInfo()['nice'] ?? 0;
    }

    public function getSystemTime(): int
    {
        return $this->getCpuInfo()['sys'] ?? 0;
    }

    public function getIdle(): int
    {
        return $this->getCpuInfo()['idle'] ?? 0;
    }

    public function getIoWait(): int
    {
        return $this->getCpuInfo()['iowait'] ?? 0;
    }

    public function getIrq(): int
    {
        return $this->getCpuInfo()['irq'] ?? 0;
    }

    public function getSoftIrq(): int
    {
        return $this->getCpuInfo()['softirq'] ?? 0;
    }

    public function getSteal(): int
    {
        return $this->getCpuInfo()['steal'] ?? 0;
    }

    public function getGuest(): int
    {
        return $this->getCpuInfo()['guest'] ?? 0;
    }

    public function getTotalTime(): int
    {
        return $this->getUserTime() + $this->getNice() + $this->getSystemTime() + 
               $this->getIdle() + $this->getIoWait() + $this->getIrq() + 
               $this->getSoftIrq() + $this->getSteal() + $this->getGuest();
    }

    public function getActiveTime(): int
    {
        return $this->getTotalTime() - $this->getIdle() - $this->getIoWait();
    }

    public function getCpuUsagePercent(): float
    {
        $total = $this->getTotalTime();
        if ($total === 0) {
            return 0.0;
        }
        return round(($this->getActiveTime() / $total) * 100, 2);
    }

    public function getPercent(): float
    {
        return $this->getCpuPercent();
    }
} 
