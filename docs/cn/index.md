# SRS PHP Client 文档

## 目录

1. [简介](#简介)
2. [安装](#安装)
3. [配置](#配置)
4. [基本使用](#基本使用)
5. [API 参考](api-reference.md)
6. [使用示例](examples.md)
7. [错误处理](error-handling.md)

## 简介

SRS PHP Client 是一个功能强大且便捷的客户端，用于与 SRS（Simple Realtime Server）API 进行交互。该库为管理 RTMP 服务器、监控流和获取统计信息提供了简单直观的接口。

### 主要特性

- 完整支持 SRS API
- 便捷的面向对象接口
- 类型化数据和 IDE 自动完成
- 详细的文档和示例
- 错误处理和异常
- SSL/TLS 支持
- 灵活配置

## 安装

```bash
composer require your-vendor/srs-php-client
```

## 配置

该库通过 `Config` 类支持灵活配置：

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

### 配置选项

| 选项 | 类型 | 默认值 | 描述 |
|------|------|--------|------|
| credentials | array | null | 认证凭据 |
| timeout | int | 30 | 请求超时（秒） |
| verify | bool/string | true | SSL 证书验证 |
| proxy | string | null | 代理设置 |
| debug | bool | false | 调试模式 |
| headers | array | [] | 附加 HTTP 头 |

## 基本使用

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    // 创建客户端
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // 获取 SRS 版本
    $version = $client->getVersion();
    echo "版本：" . $version->getVersion() . "\n";

    // 获取流列表
    $streams = $client->getStreams();
    foreach ($streams as $stream) {
        echo "流：" . $stream->getName() . "\n";
        echo "客户端数：" . $stream->getClients() . "\n";
        echo "比特率：" . $stream->getBitrateKbps() . " Kbps\n";
    }

} catch (SrsApiException $e) {
    echo "错误：" . $e->getMessage() . "\n";
}
```

更多详细信息，请参阅：
- [API 参考](api-reference.md) - 所有类和方法的完整描述
- [使用示例](examples.md) - 库的实际使用示例
- [错误处理](error-handling.md) - 错误处理和异常信息 