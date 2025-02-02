<?php

namespace SrsClient\Data;

class ProcessStats
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

    public function isOk(): bool
    {
        return $this->code === 0;
    }

    public function getProcessInfo(): array
    {
        return $this->data['process'] ?? [];
    }

    public function getThreads(): array
    {
        return $this->data['threads'] ?? [];
    }

    public function getThreadCount(): int
    {
        return count($this->getThreads());
    }

    public function getUptime(): int
    {
        return $this->getProcessInfo()['uptime'] ?? 0;
    }

    public function getUptimeSeconds(): float
    {
        return $this->getUptime() / 1000;
    }

    public function getCpuPercent(): float
    {
        return $this->getProcessInfo()['cpu_percent'] ?? 0.0;
    }

    public function getMemoryKb(): int
    {
        return $this->getProcessInfo()['mem_kb'] ?? 0;
    }

    public function getMemoryPercent(): float
    {
        return $this->getProcessInfo()['mem_percent'] ?? 0.0;
    }

    public function getCommand(): string
    {
        return $this->getProcessInfo()['comm'] ?? '';
    }

    public function getState(): string
    {
        return $this->getProcessInfo()['state'] ?? '';
    }

    public function getProcessId(): int
    {
        return (int)$this->pid;
    }

    public function getParentPid(): int
    {
        return $this->getProcessInfo()['ppid'] ?? 0;
    }

    public function getProcessGroup(): int
    {
        return $this->getProcessInfo()['pgrp'] ?? 0;
    }

    public function getSession(): int
    {
        return $this->getProcessInfo()['session'] ?? 0;
    }

    public function getTtyNr(): int
    {
        return $this->getProcessInfo()['tty_nr'] ?? 0;
    }

    public function getTpgid(): int
    {
        return $this->getProcessInfo()['tpgid'] ?? 0;
    }

    public function getPercent(): float
    {
        return $this->getCpuPercent();
    }

    public function getUserTime(): float
    {
        return $this->getProcessInfo()['ru_utime'] ?? 0.0;
    }

    public function getSystemTime(): float
    {
        return $this->getProcessInfo()['ru_stime'] ?? 0.0;
    }

    public function getChildrenUserTime(): float
    {
        return $this->getProcessInfo()['ru_cutime'] ?? 0.0;
    }

    public function getChildrenSystemTime(): float
    {
        return $this->getProcessInfo()['ru_cstime'] ?? 0.0;
    }

    public function getMinorFaults(): int
    {
        return $this->getProcessInfo()['ru_minflt'] ?? 0;
    }

    public function getMajorFaults(): int
    {
        return $this->getProcessInfo()['ru_majflt'] ?? 0;
    }

    public function getChildrenMinorFaults(): int
    {
        return $this->getProcessInfo()['ru_cminflt'] ?? 0;
    }

    public function getChildrenMajorFaults(): int
    {
        return $this->getProcessInfo()['ru_cmajflt'] ?? 0;
    }

    public function getBlockInputOperations(): int
    {
        return $this->getProcessInfo()['ru_inblock'] ?? 0;
    }

    public function getBlockOutputOperations(): int
    {
        return $this->getProcessInfo()['ru_oublock'] ?? 0;
    }

    public function getVoluntaryContextSwitches(): int
    {
        return $this->getProcessInfo()['ru_nvcsw'] ?? 0;
    }

    public function getInvoluntaryContextSwitches(): int
    {
        return $this->getProcessInfo()['ru_nivcsw'] ?? 0;
    }

    public function getTotalContextSwitches(): int
    {
        return $this->getVoluntaryContextSwitches() + $this->getInvoluntaryContextSwitches();
    }

    public function getTotalCpuTime(): float
    {
        return $this->getUserTime() + $this->getSystemTime();
    }

    public function getMaxRss(): int
    {
        return $this->getProcessInfo()['ru_maxrss'] ?? 0;
    }

    public function getThreadInfo(int $threadId): ?array
    {
        foreach ($this->getThreads() as $thread) {
            if (($thread['id'] ?? 0) === $threadId) {
                return $thread;
            }
        }
        return null;
    }

    public function getThreadCpuPercent(int $threadId): float
    {
        $thread = $this->getThreadInfo($threadId);
        return $thread['cpu_percent'] ?? 0.0;
    }

    public function getThreadState(int $threadId): string
    {
        $thread = $this->getThreadInfo($threadId);
        return $thread['state'] ?? '';
    }

    public function getGuestTime(): float
    {
        return $this->getProcessInfo()['guest_time'] ?? 0.0;
    }

    public function getCguestTime(): float
    {
        return $this->getProcessInfo()['cguest_time'] ?? 0.0;
    }

    public function getVirtualMemorySize(): int
    {
        return $this->getProcessInfo()['vsize'] ?? 0;
    }

    public function getRss(): int
    {
        return $this->getProcessInfo()['rss'] ?? 0;
    }

    public function getRssLimit(): int
    {
        return $this->getProcessInfo()['rsslim'] ?? 0;
    }

    public function getPriority(): int
    {
        return $this->getProcessInfo()['priority'] ?? 0;
    }

    public function getNice(): int
    {
        return $this->getProcessInfo()['nice'] ?? 0;
    }

    public function getNumThreads(): int
    {
        return $this->getProcessInfo()['num_threads'] ?? 0;
    }

    public function getRtPriority(): int
    {
        return $this->getProcessInfo()['rt_priority'] ?? 0;
    }

    public function getPolicy(): int
    {
        return $this->getProcessInfo()['policy'] ?? 0;
    }

    public function getStartCode(): int
    {
        return $this->getProcessInfo()['startcode'] ?? 0;
    }

    public function getEndCode(): int
    {
        return $this->getProcessInfo()['endcode'] ?? 0;
    }

    public function getStartStack(): int
    {
        return $this->getProcessInfo()['startstack'] ?? 0;
    }

    public function getSignal(): int
    {
        return $this->getProcessInfo()['signal'] ?? 0;
    }

    public function getBlocked(): int
    {
        return $this->getProcessInfo()['blocked'] ?? 0;
    }

    public function getSigignore(): int
    {
        return $this->getProcessInfo()['sigignore'] ?? 0;
    }

    public function getSigcatch(): int
    {
        return $this->getProcessInfo()['sigcatch'] ?? 0;
    }

    public function getExitSignal(): int
    {
        return $this->getProcessInfo()['exit_signal'] ?? 0;
    }
} 