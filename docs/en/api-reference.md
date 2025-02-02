# API Reference

## Classes

### Client

Main class for interacting with the SRS API.

```php
use SrsClient\Client;
```

#### Methods

##### General Information

| Method | Description | Returns |
|--------|-------------|---------|
| `getVersion()` | Get SRS version | `Version` |
| `getSummaries()` | Get general information | `Summary` |
| `getFeatures()` | Get list of features | `Features` |
| `getAuthors()` | Get authors information | `Authors` |

##### Virtual Hosts

| Method | Description | Returns |
|--------|-------------|---------|
| `getVhosts()` | Get list of virtual hosts | `VirtualHost[]` |
| `getVhost(string $vhost)` | Get information about specific host | `?VirtualHost` |

##### Streams

| Method | Description | Returns |
|--------|-------------|---------|
| `getStreams()` | Get list of streams | `Stream[]` |
| `getStream(string $streamId)` | Get stream information | `?Stream` |
| `deleteStream(string $streamId)` | Delete stream | `array` |

##### Clients

| Method | Description | Returns |
|--------|-------------|---------|
| `getClients(int $count = 10)` | Get list of clients | `Client[]` |
| `getClient(string $clientId)` | Get client information | `Client` |
| `deleteClient(string $clientId)` | Delete client | `array` |

##### System Information

| Method | Description | Returns |
|--------|-------------|---------|
| `getResourceUsage()` | Get resource usage | `ResourceUsage` |
| `getSelfProcStats()` | Get process statistics | `ProcessStats` |
| `getSystemProcStats()` | Get system statistics | `SystemStats` |
| `getMeminfos()` | Get memory information | `MemoryInfo` |

### Stream

Class for working with RTMP streams.

```php
use SrsClient\Data\Stream;
```

#### Properties

| Property | Type | Description |
|----------|------|-------------|
| id | string | Unique stream identifier |
| name | string | Stream name |
| vhost | string | Virtual host |
| app | string | Application |
| tcUrl | string | TC URL |
| url | string | Stream URL |
| liveMs | int | Lifetime in milliseconds |
| clients | int | Number of clients |
| frames | int | Number of frames |
| sendBytes | int | Bytes sent |
| recvBytes | int | Bytes received |

#### Methods

##### Basic Information

| Method | Returns | Description |
|--------|---------|-------------|
| `getId()` | string | Get stream ID |
| `getName()` | string | Get name |
| `getVhost()` | string | Get virtual host |
| `getApp()` | string | Get application |
| `getUrl()` | string | Get URL |
| `isActive()` | bool | Check if active |

##### Statistics

| Method | Returns | Description |
|--------|---------|-------------|
| `getClients()` | int | Number of clients |
| `getFrames()` | int | Number of frames |
| `getSendBytes()` | int | Bytes sent |
| `getRecvBytes()` | int | Bytes received |
| `getSendKbps()` | int | Send speed (Kbps) |
| `getRecvKbps()` | int | Receive speed (Kbps) |
| `getBitrateKbps()` | int | Total bitrate |

##### Video

| Method | Returns | Description |
|--------|---------|-------------|
| `getVideoCodec()` | string | Video codec |
| `getVideoProfile()` | string | Video profile |
| `getVideoLevel()` | string | Video level |
| `getVideoWidth()` | int | Video width |
| `getVideoHeight()` | int | Video height |

##### Audio

| Method | Returns | Description |
|--------|---------|-------------|
| `getAudioCodec()` | string | Audio codec |
| `getAudioSampleRate()` | int | Sample rate |
| `getAudioChannel()` | int | Number of channels |
| `getAudioProfile()` | string | Audio profile |

### VirtualHost

Class for working with virtual hosts.

```php
use SrsClient\Data\VirtualHost;
```

#### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `getId()` | string | Host ID |
| `getName()` | string | Host name |
| `isEnabled()` | bool | Whether enabled |
| `getClients()` | int | Number of clients |
| `getStreams()` | int | Number of streams |
| `getRecvBytes()` | int | Bytes received |
| `getSendBytes()` | int | Bytes sent |
| `getRecvKbps()` | int | Receive speed |
| `getSendKbps()` | int | Send speed |
| `isHlsEnabled()` | bool | Whether HLS enabled |
| `getHlsFragment()` | float | HLS fragment duration |

### ResourceUsage

Class for working with resource usage information.

```php
use SrsClient\Data\ResourceUsage;
```

#### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `getUserTime()` | float | User time |
| `getSystemTime()` | float | System time |
| `getMaxRss()` | int | Maximum RSS |
| `getSharedMemory()` | int | Shared memory |
| `getUnsharedData()` | int | Unshared data |
| `getUnsharedStack()` | int | Unshared stack |
| `getMinorFaults()` | int | Minor faults |
| `getMajorFaults()` | int | Major faults |
| `getSwaps()` | int | Swaps |
| `getVoluntaryContextSwitches()` | int | Voluntary context switches |
| `getInvoluntaryContextSwitches()` | int | Involuntary context switches |

### Config

Class for SRS client configuration.

```php
use SrsClient\Config;
```

#### Constructor

```php
public function __construct(string $baseUrl, array $options = [])
```

| Parameter | Type | Description |
|-----------|------|-------------|
| baseUrl | string | SRS server URL |
| options | array | Client settings |

Available options:
- `credentials` - array with `username` and `password` for authorization
- `timeout` - request timeout in seconds (default 30)
- `verify` - SSL certificate verification (default true)
- `proxy` - proxy settings
- `debug` - debug mode (default false)
- `headers` - additional HTTP headers

#### Methods

##### Basic Information

| Method | Returns | Description |
|--------|---------|-------------|
| `getBaseUrl()` | string | Get base URL |
| `getOptions()` | array | Get all settings |
| `getOption(string $key, $default = null)` | mixed | Get specific setting |
| `setOption(string $key, $value)` | self | Set setting |

##### Authorization

| Method | Returns | Description |
|--------|---------|-------------|
| `hasCredentials()` | bool | Check if authorization present |
| `getCredentials()` | ?array | Get authorization data |
| `getUsername()` | ?string | Get username |
| `getPassword()` | ?string | Get password |

##### Additional Settings

| Method | Returns | Description |
|--------|---------|-------------|
| `getTimeout()` | int | Get timeout |
| `getVerify()` | bool\|string | Get SSL settings |
| `getProxy()` | ?string | Get proxy settings |
| `getDebug()` | bool | Get debug mode |
| `getHeaders()` | array | Get additional headers | 