# Error Handling

## Exceptions

The library uses a special `SrsApiException` class to handle all errors that may occur when working with the SRS API.

```php
use SrsClient\Exception\SrsApiException;
```

## Error Types

### HTTP Errors

- 404 Not Found - resource not found
- 401 Unauthorized - authentication error
- 403 Forbidden - access denied
- 500 Internal Server Error - internal server error

### API Errors

- JSON decoding errors
- Invalid response format
- Business logic errors (code != 0)

### Configuration Errors

The following errors may occur when creating a `Config` object:

```php
try {
    // Invalid URL
    $config = new Config('invalid-url');
} catch (\InvalidArgumentException $e) {
    echo "Invalid URL format\n";
}

try {
    // Invalid timeout
    $config = new Config('http://localhost:1985', [
        'timeout' => -1
    ]);
} catch (\InvalidArgumentException $e) {
    echo "Timeout must be greater than 0\n";
}

try {
    // Invalid authorization format
    $config = new Config('http://localhost:1985', [
        'credentials' => [
            'username' => 'admin'
            // missing password
        ]
    ]);
} catch (\InvalidArgumentException $e) {
    echo "Invalid credentials format\n";
}
```

Configuration recommendations:
1. Check URL correctness before creating configuration
2. Use positive values for timeout
3. When using authorization, specify both parameters: username and password
4. Check proxy format correctness if used

## Error Handling Examples

### Basic Handling

```php
use SrsClient\Client;
use SrsClient\Config;
use SrsClient\Exception\SrsApiException;

try {
    $config = new Config('http://your-srs-server:1985');
    $client = new Client($config);
    
    $streams = $client->getStreams();
} catch (SrsApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
```

### Detailed Handling

```php
try {
    $stream = $client->getStream('stream-id');
    if ($stream === null) {
        echo "Stream not found\n";
        return;
    }
    // Working with stream
} catch (SrsApiException $e) {
    switch ($e->getCode()) {
        case 401:
            echo "Authentication required\n";
            break;
        case 403:
            echo "Access denied\n";
            break;
        case 404:
            echo "Stream not found\n";
            break;
        case 500:
            echo "Server error: " . $e->getMessage() . "\n";
            break;
        default:
            echo "Unknown error: " . $e->getMessage() . "\n";
    }
}
```

### Deletion Error Handling

```php
try {
    $client->deleteStream('stream-id');
    echo "Stream deleted successfully\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 404) {
        echo "Stream already deleted or not found\n";
    } else {
        echo "Error deleting stream: " . $e->getMessage() . "\n";
    }
}
```

### Server Availability Check

```php
try {
    $version = $client->getVersion();
    echo "Server is running. Version: " . $version->getVersion() . "\n";
} catch (SrsApiException $e) {
    if ($e->getCode() === 0) {
        echo "Cannot connect to server\n";
    } else {
        echo "Server error: " . $e->getMessage() . "\n";
    }
}
```

## Recommendations

1. **Always** handle exceptions when working with API
2. Use error code to determine the type of problem
3. Provide user-friendly error messages
4. Log detailed error information for debugging
5. Check for `null` values for methods that may not find a resource 