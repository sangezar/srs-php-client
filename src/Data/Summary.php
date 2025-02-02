<?php

namespace SrsClient\Data;

class Summary
{
    private bool $ok;
    private int $nowMs;
    private array $self;
    private array $system;
    private int $code;
    private string $server;
    private string $service;
    private string $pid;
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data['data'] ?? [];
        $this->ok = $this->data['ok'] ?? true;
        $this->nowMs = $this->data['now_ms'] ?? 0;
        $this->self = $this->data['self'] ?? [];
        $this->system = $this->data['system'] ?? [];
        $this->code = $data['code'] ?? 0;
        $this->server = $data['server'] ?? '';
        $this->service = $data['service'] ?? '';
        $this->pid = $data['pid'] ?? '';
    }

    public function isOk(): bool
    {
        return $this->ok;
    }

    public function getNowMs(): int
    {
        return $this->nowMs;
    }

    // Self info methods
    public function getVersion(): string
    {
        return $this->self['version'];
    }

    public function getPid(): string
    {
        return $this->pid;
    }

    public function getPpid(): int
    {
        return $this->self['ppid'];
    }

    public function getArgv(): string
    {
        return $this->data['argv'] ?? '';
    }

    public function getCwd(): string
    {
        return $this->data['cwd'] ?? '';
    }

    public function getMemoryKbyte(): int
    {
        return $this->self['mem_kbyte'];
    }

    public function getMemoryPercent(): float
    {
        return $this->self['mem_percent'];
    }

    public function getCpuPercent(): float
    {
        return $this->data['cpu_percent'] ?? 0.0;
    }

    public function getSrsUptime(): int
    {
        return $this->self['srs_uptime'];
    }

    public function getUptimeDuration(): string
    {
        $seconds = $this->getSrsUptime();
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%d days, %02d:%02d:%02d', $days, $hours, $minutes, $seconds);
    }

    // System info methods
    public function getSystemCpuPercent(): float
    {
        return $this->data['system_cpu_percent'] ?? 0.0;
    }

    public function getDiskReadKBps(): int
    {
        return $this->data['disk_read_KBps'] ?? 0;
    }

    public function getDiskWriteKBps(): int
    {
        return $this->data['disk_write_KBps'] ?? 0;
    }

    public function getDiskBusyPercent(): float
    {
        return $this->data['disk_busy_percent'] ?? 0.0;
    }

    public function getMemRamKbyte(): int
    {
        return $this->self['mem_ram_kbyte'];
    }

    public function getMemRamPercent(): float
    {
        return $this->self['mem_ram_percent'];
    }

    public function getMemSwapKbyte(): int
    {
        return $this->self['mem_swap_kbyte'];
    }

    public function getMemSwapPercent(): float
    {
        return $this->self['mem_swap_percent'];
    }

    public function getCpuCount(): int
    {
        return $this->data['cpus'] ?? 0;
    }

    public function getCpusOnline(): int
    {
        return $this->data['cpus_online'] ?? 0;
    }

    public function getUptime(): float
    {
        return $this->system['uptime'];
    }

    public function getIdleTime(): float
    {
        return $this->system['idle_time'];
    }

    public function getLoad1m(): float
    {
        return $this->system['load_1m'];
    }

    public function getLoad5m(): float
    {
        return $this->system['load_5m'];
    }

    public function getLoad15m(): float
    {
        return $this->system['load_15m'];
    }

    // Network statistics
    public function getNetworkRecvBytes(): int
    {
        return $this->system['net_recv_bytes'];
    }

    public function getNetworkSendBytes(): int
    {
        return $this->system['net_send_bytes'];
    }

    public function getNetworkReceivedBytes(): int
    {
        return $this->system['net_recvi_bytes'];
    }

    public function getNetworkSentBytes(): int
    {
        return $this->system['net_sendi_bytes'];
    }

    // SRS statistics
    public function getSrsRecvBytes(): int
    {
        return $this->data['srs_recv_bytes'] ?? 0;
    }

    public function getSrsSendBytes(): int
    {
        return $this->data['srs_send_bytes'] ?? 0;
    }

    // Connection statistics
    public function getConnectionsSys(): int
    {
        return $this->data['conn_sys'] ?? 0;
    }

    public function getConnectionsSysEt(): int
    {
        return $this->data['conn_sys_et'] ?? 0;
    }

    public function getConnectionsSysTw(): int
    {
        return $this->data['conn_sys_tw'] ?? 0;
    }

    public function getConnectionsSysUdp(): int
    {
        return $this->data['conn_sys_udp'] ?? 0;
    }

    public function getConnectionsSrs(): int
    {
        return $this->data['conn_srs'] ?? 0;
    }

    // Helper methods
    public function getMemoryUsageFormatted(): string
    {
        $memKb = $this->getMemoryKbyte();
        $memPercent = $this->getMemoryPercent();
        return sprintf('%.2f MB (%.2f%%)', $memKb / 1024, $memPercent);
    }

    public function getSystemMemoryFormatted(): string
    {
        $ramKb = $this->getMemRamKbyte();
        $ramPercent = $this->getMemRamPercent();
        $swapKb = $this->getMemSwapKbyte();
        $swapPercent = $this->getMemSwapPercent();
        
        return sprintf('RAM: %.2f GB (%.2f%%), Swap: %.2f GB (%.2f%%)',
            $ramKb / 1024 / 1024,
            $ramPercent,
            $swapKb / 1024 / 1024,
            $swapPercent
        );
    }

    public function getLoadAverageFormatted(): string
    {
        return sprintf('%.2f, %.2f, %.2f',
            $this->data['load_1m'] ?? 0.0,
            $this->data['load_5m'] ?? 0.0,
            $this->data['load_15m'] ?? 0.0
        );
    }

    public function getNetworkStatsFormatted(): string
    {
        $recvBytes = $this->data['net_recv_bytes'] ?? 0;
        $sendBytes = $this->data['net_send_bytes'] ?? 0;
        return sprintf('Recv: %.2f MB, Send: %.2f MB',
            $recvBytes / 1024 / 1024,
            $sendBytes / 1024 / 1024
        );
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
} 