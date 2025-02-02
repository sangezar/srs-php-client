# API 参考

## 类

### Client

用于与 SRS API 交互的主类。

```php
use SrsClient\Client;
```

#### 方法

##### 基本信息

| 方法 | 描述 | 返回值 |
|------|------|--------|
| `getVersion()` | 获取 SRS 版本 | `Version` |
| `getSummaries()` | 获取概要信息 | `Summary` |
| `getFeatures()` | 获取功能列表 | `Features` |
| `getAuthors()` | 获取作者信息 | `Authors` |

##### 虚拟主机

| 方法 | 描述 | 返回值 |
|------|------|--------|
| `getVhosts()` | 获取虚拟主机列表 | `VirtualHost[]` |
| `getVhost(string $vhost)` | 获取特定主机信息 | `?VirtualHost` |

##### 流

| 方法 | 描述 | 返回值 |
|------|------|--------|
| `getStreams()` | 获取流列表 | `Stream[]` |
| `getStream(string $streamId)` | 获取流信息 | `?Stream` |
| `deleteStream(string $streamId)` | 删除流 | `array` |

##### 客户端

| 方法 | 描述 | 返回值 |
|------|------|--------|
| `getClients(int $count = 10)` | 获取客户端列表 | `Client[]` |
| `getClient(string $clientId)` | 获取客户端信息 | `Client` |
| `deleteClient(string $clientId)` | 删除客户端 | `array` |

##### 系统信息

| 方法 | 描述 | 返回值 |
|------|------|--------|
| `getResourceUsage()` | 获取资源使用情况 | `ResourceUsage` |
| `getSelfProcStats()` | 获取进程统计信息 | `ProcessStats` |
| `getSystemProcStats()` | 获取系统统计信息 | `SystemStats` |
| `getMeminfos()` | 获取内存信息 | `MemoryInfo` |

### Stream

用于处理 RTMP 流的类。

```php
use SrsClient\Data\Stream;
```

#### 属性

| 属性 | 类型 | 描述 |
|------|------|------|
| id | string | 流唯一标识符 |
| name | string | 流名称 |
| vhost | string | 虚拟主机 |
| app | string | 应用程序 |
| tcUrl | string | TC URL |
| url | string | 流 URL |
| liveMs | int | 生存时间（毫秒） |
| clients | int | 客户端数量 |
| frames | int | 帧数 |
| sendBytes | int | 发送字节数 |
| recvBytes | int | 接收字节数 |

#### 方法

##### 基本信息

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getId()` | string | 获取流 ID |
| `getName()` | string | 获取名称 |
| `getVhost()` | string | 获取虚拟主机 |
| `getApp()` | string | 获取应用程序 |
| `getUrl()` | string | 获取 URL |
| `isActive()` | bool | 检查是否活动 |

##### 统计信息

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getClients()` | int | 客户端数量 |
| `getFrames()` | int | 帧数 |
| `getSendBytes()` | int | 发送字节数 |
| `getRecvBytes()` | int | 接收字节数 |
| `getSendKbps()` | int | 发送速率 (Kbps) |
| `getRecvKbps()` | int | 接收速率 (Kbps) |
| `getBitrateKbps()` | int | 总比特率 |

##### 视频

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getVideoCodec()` | string | 视频编解码器 |
| `getVideoProfile()` | string | 视频配置文件 |
| `getVideoLevel()` | string | 视频级别 |
| `getVideoWidth()` | int | 视频宽度 |
| `getVideoHeight()` | int | 视频高度 |

##### 音频

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getAudioCodec()` | string | 音频编解码器 |
| `getAudioSampleRate()` | int | 采样率 |
| `getAudioChannel()` | int | 声道数 |
| `getAudioProfile()` | string | 音频配置文件 |

### VirtualHost

用于处理虚拟主机的类。

```php
use SrsClient\Data\VirtualHost;
```

#### 方法

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getId()` | string | 主机 ID |
| `getName()` | string | 主机名称 |
| `isEnabled()` | bool | 是否启用 |
| `getClients()` | int | 客户端数量 |
| `getStreams()` | int | 流数量 |
| `getRecvBytes()` | int | 接收字节数 |
| `getSendBytes()` | int | 发送字节数 |
| `getRecvKbps()` | int | 接收速率 |
| `getSendKbps()` | int | 发送速率 |
| `isHlsEnabled()` | bool | 是否启用 HLS |
| `getHlsFragment()` | float | HLS 片段时长 |

### ResourceUsage

用于处理资源使用信息的类。

```php
use SrsClient\Data\ResourceUsage;
```

#### 方法

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getUserTime()` | float | 用户时间 |
| `getSystemTime()` | float | 系统时间 |
| `getMaxRss()` | int | 最大 RSS |
| `getSharedMemory()` | int | 共享内存 |
| `getUnsharedData()` | int | 非共享数据 |
| `getUnsharedStack()` | int | 非共享栈 |
| `getMinorFaults()` | int | 次要故障 |
| `getMajorFaults()` | int | 主要故障 |
| `getSwaps()` | int | 交换次数 |
| `getVoluntaryContextSwitches()` | int | 自愿上下文切换 |
| `getInvoluntaryContextSwitches()` | int | 非自愿上下文切换 |

### Config

SRS 客户端配置类。

```php
use SrsClient\Config;
```

#### 构造函数

```php
public function __construct(string $baseUrl, array $options = [])
```

| 参数 | 类型 | 描述 |
|------|------|------|
| baseUrl | string | SRS 服务器 URL |
| options | array | 客户端设置 |

可用选项：
- `credentials` - 包含 `username` 和 `password` 的认证数组
- `timeout` - 请求超时时间（秒），默认 30
- `verify` - SSL 证书验证，默认 true
- `proxy` - 代理设置
- `debug` - 调试模式，默认 false
- `headers` - 附加 HTTP 头

#### 方法

##### 基本信息

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getBaseUrl()` | string | 获取基础 URL |
| `getOptions()` | array | 获取所有设置 |
| `getOption(string $key, $default = null)` | mixed | 获取特定设置 |
| `setOption(string $key, $value)` | self | 设置选项 |

##### 认证

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `hasCredentials()` | bool | 检查是否有认证信息 |
| `getCredentials()` | ?array | 获取认证数据 |
| `getUsername()` | ?string | 获取用户名 |
| `getPassword()` | ?string | 获取密码 |

##### 附加设置

| 方法 | 返回值 | 描述 |
|------|--------|------|
| `getTimeout()` | int | 获取超时设置 |
| `getVerify()` | bool\|string | 获取 SSL 设置 |
| `getProxy()` | ?string | 获取代理设置 |
| `getDebug()` | bool | 获取调试模式 |
| `getHeaders()` | array | 获取附加头 | 