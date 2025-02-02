# Документація SRS PHP Client

## Зміст

1. [Вступ](#вступ)
2. [Встановлення](#встановлення)
3. [Конфігурація](#конфігурація)
4. [Базове використання](#базове-використання)
5. [API Reference](api-reference.md)
6. [Приклади використання](examples.md)
7. [Обробка помилок](error-handling.md)

## Вступ

SRS PHP Client - це потужний та зручний клієнт для взаємодії з API SRS (Simple RTMP Server). Бібліотека надає простий та інтуїтивний інтерфейс для управління RTMP-сервером, моніторингу потоків та отримання статистики.

### Основні можливості

- Повна підтримка API SRS
- Зручний об'єктно-орієнтований інтерфейс
- Типізовані дані та автодоповнення в IDE
- Детальна документація та приклади
- Обробка помилок та винятків
- Підтримка SSL/TLS
- Гнучка конфігурація

## Встановлення

```bash
composer require your-vendor/srs-php-client
```

## Конфігурація

Бібліотека підтримує гнучку конфігурацію через клас `Config`:

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

### Опції конфігурації

| Опція | Тип | За замовчуванням | Опис |
|-------|-----|------------------|------|
| credentials | array | null | Облікові дані для аутентифікації |
| timeout | int | 30 | Таймаут запиту в секундах |
| verify | bool/string | true | Перевірка SSL сертифіката |
| proxy | string | null | Налаштування проксі |
| debug | bool | false | Режим налагодження |
| headers | array | [] | Додаткові HTTP заголовки |

## Базове використання

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    // Створення клієнта
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);

    // Отримання версії SRS
    $version = $client->getVersion();
    echo "Версія: " . $version->getVersion() . "\n";

    // Отримання списку потоків
    $streams = $client->getStreams();
    foreach ($streams as $stream) {
        echo "Потік: " . $stream->getName() . "\n";
        echo "Клієнти: " . $stream->getClients() . "\n";
        echo "Бітрейт: " . $stream->getBitrateKbps() . " Кбіт/с\n";
    }

} catch (SrsApiException $e) {
    echo "Помилка: " . $e->getMessage() . "\n";
}
```

Для більш детальної інформації перегляньте:
- [API Reference](api-reference.md) - повний опис всіх класів та методів
- [Приклади використання](examples.md) - практичні приклади використання бібліотеки
- [Обробка помилок](error-handling.md) - інформація про обробку помилок та винятків 