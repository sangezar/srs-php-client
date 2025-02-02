<?php

namespace SrsClient\Data;

class Version
{
    private string $serverId;
    private string $serviceId;
    private string $pid;
    private int $major;
    private int $minor;
    private int $revision;
    private string $version;

    public function __construct(array $data)
    {
        $this->serverId = $data['server'];
        $this->serviceId = $data['service'];
        $this->pid = $data['pid'];
        
        // If data field is missing, create it with default version
        if (!isset($data['data'])) {
            $data['data'] = [
                'major' => 0,
                'minor' => 0,
                'revision' => 0,
                'version' => '0.0.0'
            ];
        }
        
        $this->major = $data['data']['major'];
        $this->minor = $data['data']['minor'];
        $this->revision = $data['data']['revision'];
        $this->version = $data['data']['version'];
    }

    public function getServerId(): string
    {
        return $this->serverId;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getPid(): string
    {
        return $this->pid;
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public function getRevision(): int
    {
        return $this->revision;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function isVersion(string $version): bool
    {
        return version_compare($this->version, $version, '==');
    }

    public function isNewerThan(string $version): bool
    {
        return version_compare($this->version, $version, '>');
    }

    public function isOlderThan(string $version): bool
    {
        return version_compare($this->version, $version, '<');
    }
} 