# 错误处理

## 异常

库使用特殊的 `SrsApiException` 类来处理与 SRS API 交互时可能发生的所有错误。

```php
use SrsClient\Exception\SrsApiException;
```

## 错误类型

### HTTP 错误

- 404 Not Found - 资源未找到
- 401 Unauthorized - 认证错误
- 403 Forbidden - 访问被拒绝
- 500 Internal Server Error - 服务器内部错误

### API 错误

- JSON 解码错误
- 响应格式无效
- 业务逻辑错误 (code != 0)

### 配置错误

创建 `Config` 对象时可能发生以下错误：

```php
try {
    // 无效的 URL
    $config = new Config('invalid-url');
} catch (\InvalidArgumentException $e) {
    echo "URL 格式无效\n";
}

try {
    // 无效的超时设置
    $config = new Config('http://localhost:1985', [
        'timeout' => -1
    ]);
} catch (\InvalidArgumentException $e) {
    echo "超时值必须大于 0\n";
}

try {
    // 无效的认证格式
    $config = new Config('http://localhost:1985', [
        'credentials' => [
            'username' => 'admin'
            // 缺少 password
        ]
    ]);
} catch (\InvalidArgumentException $e) {
    echo "认证格式无效\n";
}
```

配置建议：
1. 创建配置前检查 URL 的正确性
2. 超时使用正数值
3. 使用认证时，需同时指定 username 和 password
4. 如果使用代理，检查代理格式的正确性

## 错误处理示例

### 基本处理

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);
    
    $streams = $client->getStreams();
} catch (SrsApiException $e) {
    echo "错误：" . $e->getMessage() . "\n";
    echo "代码：" . $e->getCode() . "\n";
}
```

### 详细处理

```php
try {
    $stream = $client->getStream('stream-id');
    if ($stream === null) {
        echo "未找到流\n";
        return;
    }
    // 处理流
} catch (SrsApiException $e) {
    switch ($e->getCode()) {
        case 401:
            echo "需要认证\n";
            break;
        case 403:
            echo "访问被拒绝\n";
            break;
        case 404:
            echo "未找到流\n";
            break;
        case 500:
            echo "服务器错误：" . $e->getMessage() . "\n";
            break;
        default:
            echo "未知错误：" . $e->getMessage() . "\n";
    }
}
```

### 删除错误处理

```php
try {
    $client->deleteStream('stream-id');
    echo "流删除成功\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 404) {
        echo "流已被删除或未找到\n";
    } else {
        echo "删除流时出错：" . $e->getMessage() . "\n";
    }
}
```

### 服务器可用性检查

```php
try {
    $version = $client->getVersion();
    echo "服务器运行中。版本：" . $version->getVersion() . "\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 0) {
        echo "无法连接到服务器\n";
    } else {
        echo "服务器错误：" . $e->getMessage() . "\n";
    }
}
```

## 建议

1. 使用 API 时**始终**处理异常
2. 使用错误代码确定问题类型
3. 提供用户友好的错误消息
4. 记录详细的错误信息以便调试
5. 对可能找不到资源的方法检查 `null` 值 