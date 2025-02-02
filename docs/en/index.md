# SRS PHP Client Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Basic Usage](#basic-usage)
5. [API Reference](api-reference.md)
6. [Usage Examples](examples.md)
7. [Error Handling](error-handling.md)

## Introduction

SRS PHP Client is a powerful and convenient client for interacting with the SRS (Simple RTMP Server) API. The library provides a simple and intuitive interface for managing RTMP server, monitoring streams, and obtaining statistics.

### Key Features

- Full SRS API support
- Convenient object-oriented interface
- Typed data and IDE autocompletion
- Detailed documentation and examples
- Error handling and exceptions
- SSL/TLS support
- Flexible configuration

## Installation

```bash
composer require your-vendor/srs-php-client
```

## Configuration

The library supports flexible configuration through the `Config` class:

```php
use SrsClient\Config;

$config = new Config('http://your-srs-server:1985', [
    'credentials' => [
        'username' => 'admin',
        'password' => 'password'
    ],
    'timeout' => 5,
    'verify' => true,
    'debug' => false,
    'headers' => [
        'User-Agent' => 'SRS-PHP-Client/1.0'
    ]
]);
```

### Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| credentials | array | null | Authentication credentials |
| timeout | int | 30 | Request timeout in seconds |
| verify | bool/string | true | SSL certificate verification |
| proxy | string | null | Proxy settings |
| debug | bool | false | Debug mode |
| headers | array | [] | Additional HTTP headers |

## Basic Usage

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    // Create client
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Get SRS version
    $version = $client->getVersion();
    echo "Version: " . $version->getVersion() . "\n";

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

For more detailed information, see:
- [API Reference](api-reference.md) - complete description of all classes and methods
- [Usage Examples](examples.md) - practical examples of using the library
- [Error Handling](error-handling.md) - information about error handling and exceptions 