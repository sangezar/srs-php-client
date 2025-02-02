# Обробка помилок

## Винятки

Бібліотека використовує спеціальний клас `SrsApiException` для обробки всіх помилок, які можуть виникнути при роботі з API SRS.

```php
use SrsClient\Exception\SrsApiException;
```

## Типи помилок

### HTTP помилки

- 404 Not Found - ресурс не знайдено
- 401 Unauthorized - помилка аутентифікації
- 403 Forbidden - доступ заборонено
- 500 Internal Server Error - внутрішня помилка сервера

### Помилки API

- Помилки декодування JSON
- Неправильний формат відповіді
- Помилки бізнес-логіки (code != 0)

### Помилки конфігурації

При створенні об'єкта `Config` можуть виникнути наступні помилки:

```php
try {
    // Неправильний URL
    $config = new Config('invalid-url');
} catch (\InvalidArgumentException $e) {
    echo "Неправильний формат URL\n";
}

try {
    // Неправильний таймаут
    $config = new Config('http://localhost:1985', [
        'timeout' => -1
    ]);
} catch (\InvalidArgumentException $e) {
    echo "Таймаут повинен бути більше 0\n";
}

try {
    // Неправильний формат авторизації
    $config = new Config('http://localhost:1985', [
        'credentials' => [
            'username' => 'admin'
            // відсутній password
        ]
    ]);
} catch (\InvalidArgumentException $e) {
    echo "Неправильний формат облікових даних\n";
}
```

Рекомендації щодо конфігурації:
1. Перевіряйте правильність URL перед створенням конфігурації
2. Використовуйте позитивні значення для таймауту
3. При використанні авторизації вказуйте обидва параметри: username та password
4. Перевіряйте правильність формату проксі, якщо він використовується

## Приклади обробки помилок

### Базова обробка

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);
    
    $streams = $client->getStreams();
} catch (SrsApiException $e) {
    echo "Помилка: " . $e->getMessage() . "\n";
    echo "Код: " . $e->getCode() . "\n";
}
```

### Детальна обробка

```php
try {
    $stream = $client->getStream('stream-id');
    if ($stream === null) {
        echo "Потік не знайдено\n";
        return;
    }
    // Робота з потоком
} catch (SrsApiException $e) {
    switch ($e->getCode()) {
        case 401:
            echo "Потрібна аутентифікація\n";
            break;
        case 403:
            echo "Доступ заборонено\n";
            break;
        case 404:
            echo "Потік не знайдено\n";
            break;
        case 500:
            echo "Помилка сервера: " . $e->getMessage() . "\n";
            break;
        default:
            echo "Невідома помилка: " . $e->getMessage() . "\n";
    }
}
```

### Обробка помилок при видаленні

```php
try {
    $client->deleteStream('stream-id');
    echo "Потік успішно видалено\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 404) {
        echo "Потік вже видалено або не знайдено\n";
    } else {
        echo "Помилка видалення потоку: " . $e->getMessage() . "\n";
    }
}
```

### Перевірка доступності сервера

```php
try {
    $version = $client->getVersion();
    echo "Сервер працює. Версія: " . $version->getVersion() . "\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 0) {
        echo "Неможливо підключитися до сервера\n";
    } else {
        echo "Помилка сервера: " . $e->getMessage() . "\n";
    }
}
```

## Рекомендації

1. **Завжди** обробляйте винятки при роботі з API
2. Використовуйте код помилки для визначення типу проблеми
3. Надавайте користувачу зрозумілі повідомлення про помилки
4. Логуйте детальну інформацію про помилки для налагодження
5. Перевіряйте значення `null` для методів, які можуть не знайти ресурс 