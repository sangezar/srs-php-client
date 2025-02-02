# 使用示例

## 目录

1. [基本配置](#基本配置)
2. [流处理](#流处理)
3. [客户端处理](#客户端处理)
4. [虚拟主机](#虚拟主机)
5. [资源监控](#资源监控)

## 基本配置

### 简单配置

```php
use SrsClient\Client;
use SrsClient\Config;

$config = new Config('http://your-srs-server:1985');
$client = new Client($config);
```

### 高级配置

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

### 配置操作

```php
// 创建带代理的配置
$config = new Config('http://your-srs-server:1985', [
    'proxy' => 'http://proxy-server:8080',
    'timeout' => 10,
    'verify' => false
]);

// 检查认证信息
if ($config->hasCredentials()) {
    echo "用户名：" . $config->getUsername() . "\n";
}

// 获取设置
echo "超时：" . $config->getTimeout() . " 秒\n";
echo "调试模式：" . ($config->getDebug() ? '开启' : '关闭') . "\n";
echo "SSL 验证：" . ($config->getVerify() ? '是' : '否') . "\n";

// 动态修改设置
$config->setOption('timeout', 15)
       ->setOption('debug', true);

// 获取所有设置
$options = $config->getOptions();
print_r($options);

// 获取特定设置及默认值
$customHeader = $config->getOption('headers.X-Custom', 'default-value');
```

## 流处理

### 获取流列表

```php
$streams = $client->getStreams();
foreach ($streams as $stream) {
    echo "流：" . $stream->getName() . "\n";
    echo "客户端数：" . $stream->getClients() . "\n";
    echo "比特率：" . $stream->getBitrateKbps() . " Kbps\n";
    
    // 视频信息
    echo "视频：" . $stream->getVideoCodec() . " " . 
         $stream->getVideoWidth() . "x" . $stream->getVideoHeight() . "\n";
    
    // 音频信息
    echo "音频：" . $stream->getAudioCodec() . " " . 
         $stream->getAudioSampleRate() . "Hz\n";
}
```

### 获取特定流

```php
$stream = $client->getStream('stream-id');
if ($stream) {
    echo "活动状态：" . ($stream->isActive() ? '是' : '否') . "\n";
    echo "持续时间：" . $stream->getDurationInSeconds() . " 秒\n";
}
```

### 删除流

```php
try {
    $client->deleteStream('stream-id');
    echo "流删除成功\n";
} catch (SrsApiException $e) {
    echo "错误：" . $e->getMessage() . "\n";
}
```

## 客户端处理

### 获取客户端列表

```php
$clients = $client->getClients(20); // 获取20个客户端
foreach ($clients as $client) {
    echo "客户端ID：" . $client->getId() . "\n";
    echo "IP：" . $client->getIp() . "\n";
    echo "类型：" . ($client->isPublisher() ? '发布者' : '播放者') . "\n";
    
    // 统计信息
    echo "发送速率：" . $client->getSendBitrateMbps() . " Mbps\n";
    echo "接收速率：" . $client->getRecvBitrateMbps() . " Mbps\n";
}
```

### 客户端筛选

```php
// 获取发布者
$publishers = array_filter($clients, fn($client) => $client->isPublisher());

// 获取观看者
$players = array_filter($clients, fn($client) => $client->isPlayer());
```

## 虚拟主机

### 获取主机列表

```php
$vhosts = $client->getVhosts();
foreach ($vhosts as $vhost) {
    echo "虚拟主机：" . $vhost->getName() . "\n";
    echo "状态：" . ($vhost->isEnabled() ? '启用' : '禁用') . "\n";
    echo "客户端数：" . $vhost->getClients() . "\n";
    echo "流数量：" . $vhost->getStreams() . "\n";
    
    // HLS 设置
    if ($vhost->isHlsEnabled()) {
        echo "HLS 片段：" . $vhost->getHlsFragment() . " 秒\n";
    }
}
```

## 资源监控

### 资源使用

```php
$usage = $client->getResourceUsage();

echo "CPU 使用：\n";
echo "用户时间：" . $usage->getUserTime() . " 秒\n";
echo "系统时间：" . $usage->getSystemTime() . " 秒\n";

echo "\n内存使用：\n";
echo "RSS：" . $usage->getMaxRss() . " KB\n";
echo "共享：" . $usage->getSharedMemory() . " KB\n";
echo "私有：" . $usage->getUnsharedData() . " KB\n";

echo "\n页面错误：\n";
echo "次要：" . $usage->getMinorFaults() . "\n";
echo "主要：" . $usage->getMajorFaults() . "\n";

echo "\n上下文切换：\n";
echo "自愿：" . $usage->getVoluntaryContextSwitches() . "\n";
echo "非自愿：" . $usage->getInvoluntaryContextSwitches() . "\n";
```

### 系统统计

```php
$stats = $client->getSystemProcStats();
$meminfo = $client->getMeminfos();

echo "系统信息：\n";
echo "内存：" . $meminfo->getMemoryUsageFormatted() . "\n";
echo "平均负载：" . $stats->getLoadAverageFormatted() . "\n";
``` 