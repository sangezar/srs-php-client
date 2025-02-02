<?php

namespace SrsClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use SrsClient\Exception\SrsApiException;
use Psr\Http\Message\ResponseInterface;
use SrsClient\Data\Version;
use SrsClient\Data\Stream;
use SrsClient\Data\Client as SrsClient;
use SrsClient\Data\Summary;
use SrsClient\Data\Rusage;
use SrsClient\Data\ResourceUsage;
use SrsClient\Data\ProcessStats;
use SrsClient\Data\Authors;
use SrsClient\Data\Features;
use SrsClient\Data\MemoryInfo;
use SrsClient\Data\SystemStats;
use SrsClient\Data\VirtualHost;

class Client
{
    private Config $config;
    private HttpClient $httpClient;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->httpClient = new HttpClient($this->prepareClientConfig());
    }

    private function prepareClientConfig(): array
    {
        $config = [
            'base_uri' => $this->config->getBaseUrl(),
            'headers' => array_merge(
                $this->getDefaultHeaders(),
                $this->config->getOption('headers', [])
            ),
            'timeout' => $this->config->getOption('timeout', 30),
            'verify' => $this->config->getOption('verify', true),
            'debug' => $this->config->getOption('debug', false),
        ];

        if ($proxy = $this->config->getOption('proxy')) {
            $config['proxy'] = $proxy;
        }

        if ($this->config->hasCredentials()) {
            $config['auth'] = [
                $this->config->getUsername(),
                $this->config->getPassword()
            ];
        }

        return $config;
    }

    private function getDefaultHeaders(): array
    {
        return [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get SRS version
     *
     * @return Version
     * @throws SrsApiException
     */
    public function getVersion(): Version
    {
        $data = $this->sendRequest('/api/v1/versions', 'GET');
        return new Version($data);
    }

    /**
     * Get general information about SRS
     *
     * @return Summary
     * @throws SrsApiException
     */
    public function getSummaries(): Summary
    {
        $data = $this->sendRequest('/api/v1/summaries', 'GET');
        return new Summary($data);
    }

    /**
     * Get resource usage statistics
     *
     * @return ResourceUsage
     * @throws SrsApiException
     */
    public function getResourceUsage(): ResourceUsage
    {
        $data = $this->sendRequest('/api/v1/rusages', 'GET');
        return new ResourceUsage($data);
    }

    /**
     * Get SRS process statistics
     *
     * @return ProcessStats
     * @throws SrsApiException
     */
    public function getSelfProcStats(): ProcessStats
    {
        $data = $this->sendRequest('/api/v1/summaries/self', 'GET');
        return new ProcessStats($data);
    }

    /**
     * Get system process statistics
     */
    public function getSystemProcStats(): SystemStats
    {
        $data = $this->sendRequest('/api/v1/summaries/system', 'GET');
        return new SystemStats($data);
    }

    /**
     * Get system memory information
     */
    public function getMeminfos(): MemoryInfo
    {
        $data = $this->sendRequest('/api/v1/meminfos', 'GET');
        return new MemoryInfo($data);
    }

    /**
     * Get author information
     */
    public function getAuthors(): Authors
    {
        $data = $this->sendRequest('/api/v1/authors', 'GET');
        return new Authors($data);
    }

    /**
     * Get list of supported features
     */
    public function getFeatures(): Features
    {
        $data = $this->sendRequest('/api/v1/features', 'GET');
        return new Features($data);
    }

    /**
     * Get information about the current request
     */
    public function getRequests(): array
    {
        return $this->sendRequest('/api/v1/requests', 'GET');
    }

    /**
     * Get list of all virtual hosts
     */
    public function getVhosts(): array
    {
        $data = $this->sendRequest('/api/v1/vhosts', 'GET');
        $vhosts = [];
        foreach ($data['vhosts'] ?? [] as $vhostData) {
            $vhosts[] = new VirtualHost($vhostData);
        }
        return $vhosts;
    }

    /**
     * Get information about a specific virtual host
     * @param string $vhost Virtual host name
     */
    public function getVhost(string $vhost): ?VirtualHost
    {
        try {
            $data = $this->sendRequest('/api/v1/vhosts/' . urlencode($vhost), 'GET');
            return new VirtualHost($data);
        } catch (SrsApiException $e) {
            if ($e->getCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get list of all streams
     */
    public function getStreams(): array
    {
        $data = $this->sendRequest('/api/v1/streams', 'GET');
        $streams = [];
        foreach ($data['streams'] ?? [] as $streamData) {
            $streams[] = new Stream($streamData);
        }
        return $streams;
    }

    /**
     * Get information about specific stream
     * 
     * @param string $streamId Stream ID
     * @return Stream Stream information
     * @throws SrsApiException
     */
    public function getStream(string $streamId): ?Stream
    {
        try {
            $data = $this->sendRequest('/api/v1/streams/' . urlencode($streamId), 'GET');
            return new Stream($data);
        } catch (SrsApiException $e) {
            if ($e->getCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Delete stream
     * @param string $streamId Stream ID
     */
    public function deleteStream(string $streamId): array
    {
        return $this->sendRequest('/api/v1/streams/' . urlencode($streamId), 'DELETE');
    }

    /**
     * Get list of all clients
     * 
     * @param int $count Number of clients to return (default 10)
     * @return SrsClient[] Array of Client objects
     * @throws SrsApiException
     */
    public function getClients(int $count = 10): array
    {
        $data = $this->sendRequest('/api/v1/clients', 'GET', [
            'query' => ['count' => $count]
        ]);
        
        $clients = [];
        if (isset($data['clients']) && is_array($data['clients'])) {
            foreach ($data['clients'] as $clientData) {
                $clients[] = new SrsClient($clientData);
            }
        }

        return $clients;
    }

    /**
     * Get information about specific client
     * 
     * @param string $clientId Client ID
     * @return SrsClient Client information
     * @throws SrsApiException
     */
    public function getClient(string $clientId): SrsClient
    {
        $data = $this->sendRequest('/api/v1/clients/' . urlencode($clientId), 'GET');
        
        if (isset($data['clients'][0])) {
            return new SrsClient($data['clients'][0]);
        }
        
        throw new SrsApiException('Client not found');
    }

    /**
     * Delete client
     * @param string $clientId Client ID
     */
    public function deleteClient(string $clientId): array
    {
        return $this->sendRequest('/api/v1/clients/' . urlencode($clientId), 'DELETE');
    }

    /**
     * Get RAW SRS configuration
     */
    public function getRawConfig(): array
    {
        return $this->sendRequest('/api/v1/raw', 'GET');
    }

    /**
     * Cluster management
     */
    public function getClusters(): array
    {
        return $this->sendRequest('/api/v1/clusters', 'GET');
    }

    /**
     * Get system performance statistics
     */
    public function getPerformance(): array
    {
        return $this->sendRequest('/api/v1/perf', 'GET');
    }

    /**
     * Get tcmalloc information
     * @param string $page Information type ('summary' or 'api')
     */
    public function getTcmalloc(string $page = 'summary'): array
    {
        return $this->sendRequest('/api/v1/tcmalloc', 'GET', [
            'query' => ['page' => $page]
        ]);
    }

    /**
     * Send HTTP request
     */
    private function sendRequest(string $path, string $method = 'GET', array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $path, $options);
            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            throw new SrsApiException(
                $e->getMessage(),
                $e->getCode(),
                $e instanceof \Exception ? $e : null
            );
        }
    }

    /**
     * Handle API response
     */
    private function handleResponse(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SrsApiException('Failed to decode JSON response: ' . json_last_error_msg());
        }

        if (isset($data['code']) && $data['code'] !== 0) {
            $httpCode = $this->mapSrsErrorToHttpCode($data['code']);
            throw new SrsApiException($data['data'] ?? 'Unknown error', $httpCode);
        }

        return $data;
    }

    /**
     * Convert SRS API error codes to standard HTTP codes
     */
    private function mapSrsErrorToHttpCode(int $srsCode): int
    {
        $errorMap = [
            2048 => 404, // Stream not found
            2049 => 404, // Client not found
            1000 => 400, // Bad request
            1001 => 401, // Unauthorized
            1002 => 403, // Forbidden
            1003 => 404, // Not found
            1004 => 409, // Conflict
            1005 => 500, // Internal server error
        ];

        return $errorMap[$srsCode] ?? 500; // Default to 500
    }
} 
