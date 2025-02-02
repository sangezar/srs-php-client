# Usage Examples

## Table of Contents

1. [Basic Configuration](#basic-configuration)
2. [Working with Streams](#working-with-streams)
3. [Working with Clients](#working-with-clients)
4. [Virtual Hosts](#virtual-hosts)
5. [Resource Monitoring](#resource-monitoring)

## Basic Configuration

### Simple Configuration

```php
use SrsClient\Client;
use SrsClient\Config;

$config = new Config('http://your-srs-server:1985');
$client = new Client($config);
```

### Advanced Configuration

```php
$config = new Config('http://your-srs-server:1985', [
    'credentials' => [
        'username' => 'admin',
        'password' => 'password'
    ],
    'timeout' => 5,
    'verify' => true,
    'debug' => true,
    'headers' => [
        'User-Agent' => 'Custom-App/1.0'
    ]
]);
```

### Working with Configuration

```php
// Creating configuration with proxy
$config = new Config('http://your-srs-server:1985', [
    'proxy' => 'http://proxy-server:8080',
    'timeout' => 10,
    'verify' => false
]);

// Checking for authorization
if ($config->hasCredentials()) {
    echo "Username: " . $config->getUsername() . "\n";
}

// Getting settings
echo "Timeout: " . $config->getTimeout() . " seconds\n";
echo "Debug Mode: " . ($config->getDebug() ? 'On' : 'Off') . "\n";
echo "SSL Verify: " . ($config->getVerify() ? 'Yes' : 'No') . "\n";

// Dynamically changing settings
$config->setOption('timeout', 15)
       ->setOption('debug', true);

// Getting all settings
$options = $config->getOptions();
print_r($options);

// Getting specific setting with default value
$customHeader = $config->getOption('headers.X-Custom', 'default-value');
```

## Working with Streams

### Getting Stream List

```php
$streams = $client->getStreams();
foreach ($streams as $stream) {
    echo "Stream: " . $stream->getName() . "\n";
    echo "Clients: " . $stream->getClients() . "\n";
    echo "Bitrate: " . $stream->getBitrateKbps() . " Kbps\n";
    
    // Video information
    echo "Video: " . $stream->getVideoCodec() . " " . 
         $stream->getVideoWidth() . "x" . $stream->getVideoHeight() . "\n";
    
    // Audio information
    echo "Audio: " . $stream->getAudioCodec() . " " . 
         $stream->getAudioSampleRate() . "Hz\n";
}
```

### Getting Specific Stream

```php
$stream = $client->getStream('stream-id');
if ($stream) {
    echo "Active: " . ($stream->isActive() ? 'Yes' : 'No') . "\n";
    echo "Duration: " . $stream->getDurationInSeconds() . " seconds\n";
}
```

### Deleting Stream

```php
try {
    $client->deleteStream('stream-id');
    echo "Stream deleted successfully\n";
} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Working with Clients

### Getting Client List

```php
$clients = $client->getClients(20); // get 20 clients
foreach ($clients as $client) {
    echo "Client ID: " . $client->getId() . "\n";
    echo "IP: " . $client->getIp() . "\n";
    echo "Type: " . ($client->isPublisher() ? 'Publisher' : 'Player') . "\n";
    
    // Statistics
    echo "Send Rate: " . $client->getSendBitrateMbps() . " Mbps\n";
    echo "Recv Rate: " . $client->getRecvBitrateMbps() . " Mbps\n";
}
```

### Filtering Clients

```php
// Getting only publishers
$publishers = array_filter($clients, fn($client) => $client->isPublisher());

// Getting only viewers
$players = array_filter($clients, fn($client) => $client->isPlayer());
```

## Virtual Hosts

### Getting Host List

```php
$vhosts = $client->getVhosts();
foreach ($vhosts as $vhost) {
    echo "VHost: " . $vhost->getName() . "\n";
    echo "Status: " . ($vhost->isEnabled() ? 'Enabled' : 'Disabled') . "\n";
    echo "Clients: " . $vhost->getClients() . "\n";
    echo "Streams: " . $vhost->getStreams() . "\n";
    
    // HLS settings
    if ($vhost->isHlsEnabled()) {
        echo "HLS Fragment: " . $vhost->getHlsFragment() . " seconds\n";
    }
}
```

## Resource Monitoring

### Resource Usage

```php
$usage = $client->getResourceUsage();

echo "CPU Usage:\n";
echo "User Time: " . $usage->getUserTime() . " seconds\n";
echo "System Time: " . $usage->getSystemTime() . " seconds\n";

echo "\nMemory Usage:\n";
echo "RSS: " . $usage->getMaxRss() . " KB\n";
echo "Shared: " . $usage->getSharedMemory() . " KB\n";
echo "Private: " . $usage->getUnsharedData() . " KB\n";

echo "\nPage Faults:\n";
echo "Minor: " . $usage->getMinorFaults() . "\n";
echo "Major: " . $usage->getMajorFaults() . "\n";

echo "\nContext Switches:\n";
echo "Voluntary: " . $usage->getVoluntaryContextSwitches() . "\n";
echo "Involuntary: " . $usage->getInvoluntaryContextSwitches() . "\n";
```

### System Statistics

```php
$stats = $client->getSystemProcStats();
$meminfo = $client->getMeminfos();

echo "System Information:\n";
echo "Memory: " . $meminfo->getMemoryUsageFormatted() . "\n";
echo "Load Average: " . $stats->getLoadAverageFormatted() . "\n";
``` 