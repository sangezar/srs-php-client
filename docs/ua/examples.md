# Приклади використання

## Зміст

1. [Базова конфігурація](#базова-конфігурація)
2. [Робота з потоками](#робота-з-потоками)
3. [Робота з клієнтами](#робота-з-клієнтами)
4. [Віртуальні хости](#віртуальні-хости)
5. [Моніторинг ресурсів](#моніторинг-ресурсів)

## Базова конфігурація

### Проста конфігурація

```php
use SrsClient\Client;
use SrsClient\Config;

$config = new Config('http://your-srs-server:1985');
$client = new Client($config);
```

### Розширена конфігурація

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

### Робота з конфігурацією

```php
// Створення конфігурації з проксі
$config = new Config('http://your-srs-server:1985', [
    'proxy' => 'http://proxy-server:8080',
    'timeout' => 10,
    'verify' => false
]);

// Перевірка наявності авторизації
if ($config->hasCredentials()) {
    echo "Користувач: " . $config->getUsername() . "\n";
}

// Отримання налаштувань
echo "Таймаут: " . $config->getTimeout() . " секунд\n";
echo "Режим налагодження: " . ($config->getDebug() ? 'Увімк.' : 'Вимк.') . "\n";
echo "SSL перевірка: " . ($config->getVerify() ? 'Так' : 'Ні') . "\n";

// Динамічна зміна налаштувань
$config->setOption('timeout', 15)
       ->setOption('debug', true);

// Отримання всіх налаштувань
$options = $config->getOptions();
print_r($options);

// Отримання конкретного налаштування з значенням за замовчуванням
$customHeader = $config->getOption('headers.X-Custom', 'default-value');
```

## Робота з потоками

### Отримання списку потоків

```php
$streams = $client->getStreams();
foreach ($streams as $stream) {
    echo "Потік: " . $stream->getName() . "\n";
    echo "Клієнти: " . $stream->getClients() . "\n";
    echo "Бітрейт: " . $stream->getBitrateKbps() . " Кбіт/с\n";
    
    // Відео інформація
    echo "Відео: " . $stream->getVideoCodec() . " " . 
         $stream->getVideoWidth() . "x" . $stream->getVideoHeight() . "\n";
    
    // Аудіо інформація
    echo "Аудіо: " . $stream->getAudioCodec() . " " . 
         $stream->getAudioSampleRate() . "Гц\n";
}
```

### Отримання конкретного потоку

```php
$stream = $client->getStream('stream-id');
if ($stream) {
    echo "Активний: " . ($stream->isActive() ? 'Так' : 'Ні') . "\n";
    echo "Тривалість: " . $stream->getDurationInSeconds() . " секунд\n";
}
```

### Видалення потоку

```php
try {
    $client->deleteStream('stream-id');
    echo "Потік успішно видалено\n";
} catch (SrsApiException $e) {
    echo "Помилка: " . $e->getMessage() . "\n";
}
```

## Робота з клієнтами

### Отримання списку клієнтів

```php
$clients = $client->getClients(20); // отримати 20 клієнтів
foreach ($clients as $client) {
    echo "ID клієнта: " . $client->getId() . "\n";
    echo "IP: " . $client->getIp() . "\n";
    echo "Тип: " . ($client->isPublisher() ? 'Видавець' : 'Глядач') . "\n";
    
    // Статистика
    echo "Швидкість відправки: " . $client->getSendBitrateMbps() . " Мбіт/с\n";
    echo "Швидкість отримання: " . $client->getRecvBitrateMbps() . " Мбіт/с\n";
}
```

### Фільтрація клієнтів

```php
// Отримання тільки видавців
$publishers = array_filter($clients, fn($client) => $client->isPublisher());

// Отримання тільки глядачів
$players = array_filter($clients, fn($client) => $client->isPlayer());
```

## Віртуальні хости

### Отримання списку хостів

```php
$vhosts = $client->getVhosts();
foreach ($vhosts as $vhost) {
    echo "VHost: " . $vhost->getName() . "\n";
    echo "Статус: " . ($vhost->isEnabled() ? 'Увімкнено' : 'Вимкнено') . "\n";
    echo "Клієнти: " . $vhost->getClients() . "\n";
    echo "Потоки: " . $vhost->getStreams() . "\n";
    
    // HLS налаштування
    if ($vhost->isHlsEnabled()) {
        echo "HLS фрагмент: " . $vhost->getHlsFragment() . " секунд\n";
    }
}
```

## Моніторинг ресурсів

### Використання ресурсів

```php
$usage = $client->getResourceUsage();

echo "Використання CPU:\n";
echo "Час користувача: " . $usage->getUserTime() . " секунд\n";
echo "Системний час: " . $usage->getSystemTime() . " секунд\n";

echo "\nВикористання пам'яті:\n";
echo "RSS: " . $usage->getMaxRss() . " КБ\n";
echo "Спільна: " . $usage->getSharedMemory() . " КБ\n";
echo "Приватна: " . $usage->getUnsharedData() . " КБ\n";

echo "\nПомилки сторінок:\n";
echo "Незначні: " . $usage->getMinorFaults() . "\n";
echo "Значні: " . $usage->getMajorFaults() . "\n";

echo "\nПеремикання контексту:\n";
echo "Добровільні: " . $usage->getVoluntaryContextSwitches() . "\n";
echo "Примусові: " . $usage->getInvoluntaryContextSwitches() . "\n";
```

### Системна статистика

```php
$stats = $client->getSystemProcStats();
$meminfo = $client->getMeminfos();

echo "Системна інформація:\n";
echo "Пам'ять: " . $meminfo->getMemoryUsageFormatted() . "\n";
echo "Середнє навантаження: " . $stats->getLoadAverageFormatted() . "\n";
``` 