<?php

namespace SrsClient\Data;

class ResourceUsage
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

    public function getResourceInfo(): array
    {
        return $this->data['rusage'] ?? [];
    }

    public function getUserTime(): float
    {
        return $this->getResourceInfo()['ru_utime'] ?? 0.0;
    }

    public function getSystemTime(): float
    {
        return $this->getResourceInfo()['ru_stime'] ?? 0.0;
    }

    public function getMaxRss(): int
    {
        return $this->getResourceInfo()['ru_maxrss'] ?? 0;
    }

    public function getMinorFaults(): int
    {
        return $this->getResourceInfo()['ru_minflt'] ?? 0;
    }

    public function getMajorFaults(): int
    {
        return $this->getResourceInfo()['ru_majflt'] ?? 0;
    }

    public function getBlockInputOperations(): int
    {
        return $this->getResourceInfo()['ru_inblock'] ?? 0;
    }

    public function getBlockOutputOperations(): int
    {
        return $this->getResourceInfo()['ru_oublock'] ?? 0;
    }

    public function getVoluntaryContextSwitches(): int
    {
        return $this->getResourceInfo()['ru_nvcsw'] ?? 0;
    }

    public function getInvoluntaryContextSwitches(): int
    {
        return $this->getResourceInfo()['ru_nivcsw'] ?? 0;
    }

    public function getTotalContextSwitches(): int
    {
        return $this->getVoluntaryContextSwitches() + $this->getInvoluntaryContextSwitches();
    }

    public function getTotalCpuTime(): float
    {
        return $this->getUserTime() + $this->getSystemTime();
    }

    public function isOk(): bool
    {
        return $this->code === 0;
    }

    public function getSharedMemory(): int
    {
        return $this->getResourceInfo()['ru_ixrss'] ?? 0;
    }

    public function getUnsharedData(): int
    {
        return $this->getResourceInfo()['ru_idrss'] ?? 0;
    }

    public function getUnsharedStack(): int
    {
        return $this->getResourceInfo()['ru_isrss'] ?? 0;
    }

    public function getSwaps(): int
    {
        return $this->getResourceInfo()['ru_nswap'] ?? 0;
    }

    public function getInputOperations(): int
    {
        return $this->getBlockInputOperations();
    }

    public function getOutputOperations(): int
    {
        return $this->getBlockOutputOperations();
    }

    public function getMessagesSent(): int
    {
        return $this->getResourceInfo()['ru_msgsnd'] ?? 0;
    }

    public function getMessagesReceived(): int
    {
        return $this->getResourceInfo()['ru_msgrcv'] ?? 0;
    }

    public function getSignalsReceived(): int
    {
        return $this->getResourceInfo()['ru_nsignals'] ?? 0;
    }
} 