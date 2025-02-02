# SRS PHP Client

PHP client for interacting with SRS (Simple RTMP Server) API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sangezar/srs-php-client.svg?style=flat-square)](https://packagist.org/packages/sangezar/srs-php-client)
[![Total Downloads](https://img.shields.io/packagist/dt/sangezar/srs-php-client.svg?style=flat-square)](https://packagist.org/packages/sangezar/srs-php-client)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Features

- 🚀 Full SRS API support
- 🔧 Convenient object-oriented interface
- 📝 Typed data and IDE autocompletion
- 📚 Detailed documentation and examples
- 🛡️ Error handling and exceptions
- 🔒 SSL/TLS support
- ⚙️ Flexible configuration

## Installation

```bash
composer require sangezar/srs-php-client
```

## Quick Start

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    // Create client
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get list of streams
    $streams = $client->getStreams();
    foreach ($streams as $stream) {
        echo "Stream: " . $stream->getName() . "\n";
        echo "Clients: " . $stream->getClients() . "\n";
        echo "Bitrate: " . $stream->getBitrateKbps() . " Kbps\n";
    }

} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Documentation

Detailed documentation is available in multiple languages in the [docs](docs) directory:

- [English Documentation](docs/en/index.md)
- [Ukrainian Documentation](docs/ua/index.md)
- [Chinese Documentation](docs/cn/index.md)

## Requirements

- PHP 7.4 or higher
- Composer
- PHP Extensions:
  - JSON
  - cURL

## Testing

```bash
composer test
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Authors

- [Sangezar](https://github.com/sangezar)

## Support

If you encounter any issues or have suggestions for improvements, please:

1. Check [existing issues](https://github.com/sangezar/srs-php-client/issues)
2. Create a new issue with a detailed description
3. Submit a pull request with fixes or improvements

## Changelog

All notable changes are documented in the [CHANGELOG.md](CHANGELOG.md) file.

## Usage

### Basic Usage

```php
use SrsClient\Client;
use SrsClient\Config;

// Create configuration with credentials
$config = new Config('http://your-srs-server:1985', [
    'credentials' => [
        'username' => 'admin',
        'password' => 'password'
    ],
    'timeout' => 5,
    'verify' => true,
    'debug' => false
]);

// Initialize client
$client = new Client($config);

// Get SRS version
$version = $client->getVersions();
echo "Version: " . $version->getVersion() . "\n";
echo "Server ID: " . $version->getServerId() . "\n";
echo "Service ID: " . $version->getServiceId() . "\n";

// Check version
if ($version->isNewerThan('5.0.0')) {
    echo "Running on newer version than 5.0.0\n";
}

// Working with streams
$streams = $client->getStreams();
$streamInfo = $client->getStream('stream-id');
$client->deleteStream('stream-id');

// Working with clients
$clients = $client->getClients(20); // get 20 clients
$clientInfo = $client->getClient('client-id');
$client->deleteClient('client-id');
```

### Available Settings

Configuration supports the following options:

- `credentials` - Authentication credentials:
  - `username` - Username
  - `password` - Password
- `timeout` - Request timeout in seconds (default: 30)
- `verify` - SSL certificate verification (true/false or path to CA bundle)
- `proxy` - Proxy settings
- `debug` - Debug mode (true/false)
- `headers` - Additional HTTP headers

### Available Methods

#### System Information
- `getVersions()` - Get SRS version
  - Returns `Version` object with methods:
    - `getVersion()` - Full version (e.g., "5.0.213")
    - `getServerId()` - Unique server ID
    - `getServiceId()` - Unique service ID
    - `getPid()` - Process ID
    - `getMajor()` - Major version
    - `getMinor()` - Minor version
    - `getRevision()` - Revision
    - `isVersion(string $version)` - Check specific version
    - `isNewerThan(string $version)` - Check if version is newer
    - `isOlderThan(string $version)` - Check if version is older
- `getSummaries()` - Get general information (pid, argv, pwd, cpu, mem)
- `getRusages()` - Get resource usage statistics
- `getSelfProcStats()` - Get SRS process statistics
- `getSystemProcStats()` - Get system process statistics
- `getMeminfos()` - Get system memory information
- `getPerformance()` - Get system performance statistics

#### Server Information
- `getAuthors()` - Get information about authors, license, and contributors
- `getFeatures()` - Get list of supported features
- `getRequests()` - Get current request information
- `getRawConfig()` - Get RAW SRS configuration

#### Virtual Host Management
- `getVhosts()` - Get list of all virtual hosts
- `getVhost(string $vhost)` - Get information about specific virtual host

#### Stream Management
- `getStreams()` - Get list of all streams
- `getStream(string $streamId)` - Get information about specific stream
- `deleteStream(string $streamId)` - Delete stream

#### Client Management
- `getClients(int $count = 10)` - Get list of clients (default 10)
- `getClient(string $clientId)` - Get information about specific client
- `deleteClient(string $clientId)` - Delete client

#### Cluster and Diagnostics
- `getClusters()` - Cluster management
- `getTcmalloc(string $page = 'summary')` - Get tcmalloc information

### Error Handling

```php
use SrsClient\Exception\SrsApiException;

try {
    $streams = $client->getStreams();
} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage();
}
``` 