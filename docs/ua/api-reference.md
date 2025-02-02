# API Reference

## Класи

### Client

Основний клас для взаємодії з API SRS.

```php
use SrsClient\Client;
```

#### Методи

##### Загальна інформація

| Метод | Опис | Повертає |
|-------|------|----------|
| `getVersion()` | Отримати версію SRS | `Version` |
| `getSummaries()` | Отримати загальну інформацію | `Summary` |
| `getFeatures()` | Отримати список функцій | `Features` |
| `getAuthors()` | Отримати інформацію про авторів | `Authors` |

##### Віртуальні хости

| Метод | Опис | Повертає |
|-------|------|----------|
| `getVhosts()` | Отримати список віртуальних хостів | `VirtualHost[]` |
| `getVhost(string $vhost)` | Отримати інформацію про конкретний хост | `?VirtualHost` |

##### Потоки

| Метод | Опис | Повертає |
|-------|------|----------|
| `getStreams()` | Отримати список потоків | `Stream[]` |
| `getStream(string $streamId)` | Отримати інформацію про потік | `?Stream` |
| `deleteStream(string $streamId)` | Видалити потік | `array` |

##### Клієнти

| Метод | Опис | Повертає |
|-------|------|----------|
| `getClients(int $count = 10)` | Отримати список клієнтів | `Client[]` |
| `getClient(string $clientId)` | Отримати інформацію про клієнта | `Client` |
| `deleteClient(string $clientId)` | Видалити клієнта | `array` |

##### Системна інформація

| Метод | Опис | Повертає |
|-------|------|----------|
| `getResourceUsage()` | Отримати використання ресурсів | `ResourceUsage` |
| `getSelfProcStats()` | Отримати статистику процесу | `ProcessStats` |
| `getSystemProcStats()` | Отримати системну статистику | `SystemStats` |
| `getMeminfos()` | Отримати інформацію про пам'ять | `MemoryInfo` |

### Stream

Клас для роботи з RTMP потоками.

```php
use SrsClient\Data\Stream;
```

#### Властивості

| Властивість | Тип | Опис |
|-------------|-----|------|
| id | string | Унікальний ідентифікатор потоку |
| name | string | Назва потоку |
| vhost | string | Віртуальний хост |
| app | string | Додаток |
| tcUrl | string | TC URL |
| url | string | URL потоку |
| liveMs | int | Час життя в мілісекундах |
| clients | int | Кількість клієнтів |
| frames | int | Кількість кадрів |
| sendBytes | int | Відправлено байт |
| recvBytes | int | Отримано байт |

#### Методи

##### Основна інформація

| Метод | Повертає | Опис |
|-------|----------|------|
| `getId()` | string | Отримати ID потоку |
| `getName()` | string | Отримати назву |
| `getVhost()` | string | Отримати віртуальний хост |
| `getApp()` | string | Отримати додаток |
| `getUrl()` | string | Отримати URL |
| `isActive()` | bool | Перевірити чи активний |

##### Статистика

| Метод | Повертає | Опис |
|-------|----------|------|
| `getClients()` | int | Кількість клієнтів |
| `getFrames()` | int | Кількість кадрів |
| `getSendBytes()` | int | Відправлено байт |
| `getRecvBytes()` | int | Отримано байт |
| `getSendKbps()` | int | Швидкість відправки (Kbps) |
| `getRecvKbps()` | int | Швидкість отримання (Kbps) |
| `getBitrateKbps()` | int | Загальний бітрейт |

##### Відео

| Метод | Повертає | Опис |
|-------|----------|------|
| `getVideoCodec()` | string | Кодек відео |
| `getVideoProfile()` | string | Профіль відео |
| `getVideoLevel()` | string | Рівень відео |
| `getVideoWidth()` | int | Ширина відео |
| `getVideoHeight()` | int | Висота відео |

##### Аудіо

| Метод | Повертає | Опис |
|-------|----------|------|
| `getAudioCodec()` | string | Кодек аудіо |
| `getAudioSampleRate()` | int | Частота дискретизації |
| `getAudioChannel()` | int | Кількість каналів |
| `getAudioProfile()` | string | Профіль аудіо |

### VirtualHost

Клас для роботи з віртуальними хостами.

```php
use SrsClient\Data\VirtualHost;
```

#### Методи

| Метод | Повертає | Опис |
|-------|----------|------|
| `getId()` | string | ID хоста |
| `getName()` | string | Назва хоста |
| `isEnabled()` | bool | Чи увімкнено |
| `getClients()` | int | Кількість клієнтів |
| `getStreams()` | int | Кількість потоків |
| `getRecvBytes()` | int | Отримано байт |
| `getSendBytes()` | int | Відправлено байт |
| `getRecvKbps()` | int | Швидкість отримання |
| `getSendKbps()` | int | Швидкість відправки |
| `isHlsEnabled()` | bool | Чи увімкнено HLS |
| `getHlsFragment()` | float | Тривалість HLS фрагмента |

### ResourceUsage

Клас для роботи з інформацією про використання ресурсів.

```php
use SrsClient\Data\ResourceUsage;
```

#### Методи

| Метод | Повертає | Опис |
|-------|----------|------|
| `getUserTime()` | float | Час користувача |
| `getSystemTime()` | float | Системний час |
| `getMaxRss()` | int | Максимальний RSS |
| `getSharedMemory()` | int | Спільна пам'ять |
| `getUnsharedData()` | int | Неспільні дані |
| `getUnsharedStack()` | int | Неспільний стек |
| `getMinorFaults()` | int | Незначні помилки |
| `getMajorFaults()` | int | Значні помилки |
| `getSwaps()` | int | Свопи |
| `getVoluntaryContextSwitches()` | int | Добровільні перемикання контексту |
| `getInvoluntaryContextSwitches()` | int | Примусові перемикання контексту |

### Config

Клас для конфігурації клієнта SRS.

```php
use SrsClient\Config;
```

#### Конструктор

```php
public function __construct(string $baseUrl, array $options = [])
```

| Параметр | Тип | Опис |
|----------|-----|------|
| baseUrl | string | URL SRS сервера |
| options | array | Налаштування клієнта |

Доступні опції:
- `credentials` - масив з `username` та `password` для авторизації
- `timeout` - таймаут запиту в секундах (за замовчуванням 30)
- `verify` - перевірка SSL сертифіката (за замовчуванням true)
- `proxy` - налаштування проксі
- `debug` - режим налагодження (за замовчуванням false)
- `headers` - додаткові HTTP заголовки

#### Методи

##### Базова інформація

| Метод | Повертає | Опис |
|-------|----------|------|
| `getBaseUrl()` | string | Отримати базовий URL |
| `getOptions()` | array | Отримати всі налаштування |
| `getOption(string $key, $default = null)` | mixed | Отримати конкретне налаштування |
| `setOption(string $key, $value)` | self | Встановити налаштування |

##### Авторизація

| Метод | Повертає | Опис |
|-------|----------|------|
| `hasCredentials()` | bool | Перевірити наявність авторизації |
| `getCredentials()` | ?array | Отримати дані авторизації |
| `getUsername()` | ?string | Отримати ім'я користувача |
| `getPassword()` | ?string | Отримати пароль |

##### Додаткові налаштування

| Метод | Повертає | Опис |
|-------|----------|------|
| `getTimeout()` | int | Отримати таймаут |
| `getVerify()` | bool\|string | Отримати налаштування SSL |
| `getProxy()` | ?string | Отримати налаштування проксі |
| `getDebug()` | bool | Отримати режим налагодження |
| `getHeaders()` | array | Отримати додаткові заголовки | 