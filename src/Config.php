<?php

namespace SrsClient;

/**
 * Configuration class for SRS client
 *
 * @package SrsClient
 */
class Config
{
    private string $baseUrl;
    private array $options;

    /**
     * @param string $baseUrl SRS server URL
     * @param array $options Client settings:
     *  - credentials: array Authentication credentials
     *    - username: string
     *    - password: string
     *  - timeout: int Request timeout in seconds
     *  - verify: bool|string SSL certificate verification
     *  - proxy: string Proxy settings
     *  - debug: bool Debug mode
     *  - headers: array Additional HTTP headers
     */
    public function __construct(string $baseUrl, array $options = [])
    {
        // Validate baseUrl
        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid base URL format');
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        
        // Set default values
        $this->options = array_merge([
            'credentials' => null,
            'timeout' => 30,
            'verify' => true,
            'proxy' => null,
            'debug' => false,
            'headers' => [],
        ], $options);

        // Validate timeout
        if (isset($this->options['timeout']) && $this->options['timeout'] <= 0) {
            throw new \InvalidArgumentException('Timeout must be greater than 0');
        }

        // Validate credentials
        if (isset($this->options['credentials'])) {
            if (!is_array($this->options['credentials']) ||
                !isset($this->options['credentials']['username']) ||
                !isset($this->options['credentials']['password'])) {
                throw new \InvalidArgumentException('Invalid credentials format');
            }
        }
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function hasCredentials(): bool
    {
        return isset($this->options['credentials']['username']) 
            && isset($this->options['credentials']['password']);
    }

    public function getUsername(): ?string
    {
        return $this->options['credentials']['username'] ?? null;
    }

    public function getPassword(): ?string
    {
        return $this->options['credentials']['password'] ?? null;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function getCredentials(): ?array
    {
        return $this->options['credentials'];
    }

    public function getTimeout(): int
    {
        return $this->options['timeout'];
    }

    public function getVerify()
    {
        return $this->options['verify'];
    }

    public function getDebug(): bool
    {
        return $this->options['debug'];
    }

    public function getHeaders(): array
    {
        return $this->options['headers'];
    }

    public function getProxy(): ?string
    {
        return $this->options['proxy'];
    }
} 
