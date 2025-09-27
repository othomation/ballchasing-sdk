# Ballchasing Laravel SDK

A Laravel package for integrating with the [Ballchasing.com](https://ballchasing.com) API, providing easy access to Rocket League replay data and analysis.

## Features

- ✅ **Replay Management**: List, retrieve, upload, update, and delete replays
- ✅ **Group Management**: Create and manage replay groups
- ✅ **Maps Information**: Get available Rocket League maps
- ✅ **Laravel Integration**: Service provider with dependency injection
- ✅ **Type Safety**: Full PHP 8.2+ type declarations and DTOs
- ✅ **Testing**: Comprehensive test suite with mocked HTTP responses
- ✅ **Error Handling**: Custom exceptions with detailed error messages
- ✅ **Retry Logic**: Automatic retry for server errors

## Installation

Install the package via Composer:

```bash
composer require lucie/ballchasing-laravel
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=ballchasing-config
```

Add your Ballchasing API key to your `.env` file:

```env
BALLCHASING_API_KEY=your_api_key_here
```

You can get an API key from [Ballchasing.com](https://ballchasing.com/upload) after logging in.

## Usage

### Basic Usage

Inject the client into your classes:

```php
use Lucie\BallchasingLaravel\Contracts\BallchasingClientInterface;

class ReplayController extends Controller
{
    public function __construct(
        private BallchasingClientInterface $ballchasing
    ) {}

    public function index()
    {
        $replays = $this->ballchasing->getReplays([
            'count' => 10,
            'sort-by' => 'created',
            'sort-dir' => 'desc'
        ]);

        return view('replays.index', compact('replays'));
    }
}
```

### Available Methods

#### Replays

```php
// Get replays with optional filters
$replays = $ballchasing->getReplays([
    'title' => 'Grand Final',
    'playlist' => 'ranked-standard',
    'count' => 50
]);

// Get a specific replay
$replay = $ballchasing->getReplay('replay-id');

// Upload a replay file
$replay = $ballchasing->uploadReplay('/path/to/replay.replay', [
    'title' => 'My Awesome Replay',
    'visibility' => 'public'
]);

// Update replay metadata
$replay = $ballchasing->updateReplay('replay-id', [
    'title' => 'Updated Title'
]);

// Delete a replay
$ballchasing->deleteReplay('replay-id');
```

#### Groups

```php
// Get groups
$groups = $ballchasing->getGroups(['count' => 10]);

// Get a specific group
$group = $ballchasing->getGroup('group-id');

// Create a new group
$group = $ballchasing->createGroup([
    'name' => 'Tournament Replays',
    'type' => 'regular'
]);

// Update a group
$group = $ballchasing->updateGroup('group-id', [
    'name' => 'Updated Group Name'
]);

// Delete a group
$ballchasing->deleteGroup('group-id');
```

#### Maps

```php
// Get all available maps
$maps = $ballchasing->getMaps();
// Returns: ['stadium_p' => 'DFH Stadium', 'park_p' => 'Beckwith Park', ...]
```

### Data Transfer Objects (DTOs)

The package uses DTOs for type safety and better IDE support:

```php
$replays = $ballchasing->getReplays();

foreach ($replays->replays as $replay) {
    echo $replay->id;        // string
    echo $replay->title;     // string
    echo $replay->created;   // string (ISO date)
    echo $replay->duration;  // ?int (seconds)
    echo $replay->mapCode;   // ?string
}

echo $replays->count; // Total number of replays
```

### Error Handling

The package throws custom exceptions for different error scenarios:

```php
use Lucie\BallchasingLaravel\Exceptions\BallchasingException;

try {
    $replay = $ballchasing->getReplay('invalid-id');
} catch (BallchasingException $e) {
    // Handle API errors
    echo $e->getMessage();
    echo $e->getCode(); // HTTP status code
}
```

### Configuration Options

The `config/ballchasing.php` file allows you to configure:

```php
return [
    'api_key' => env('BALLCHASING_API_KEY'),
    'base_url' => env('BALLCHASING_BASE_URL', 'https://ballchasing.com/api'),
    'timeout' => env('BALLCHASING_TIMEOUT', 30),
    'retry_attempts' => env('BALLCHASING_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('BALLCHASING_RETRY_DELAY', 500), // milliseconds
];
```

## Testing

The package includes two types of tests:

### Unit Tests (Mocked)

Run unit tests with mocked HTTP responses (no API key required):

```bash
composer test
# or specifically
composer test-unit
```

### Integration Tests (Real API)

For integration testing with the real Ballchasing API:

1. Copy the example environment file:
   ```bash
   cp .env.testing .env
   ```

2. Add your real API key to `.env`:
   ```env
   BALLCHASING_API_KEY=your_real_api_key_here
   ```

3. Run integration tests:
   ```bash
   composer test-integration
   ```

### Coverage Reports

Generate test coverage reports:

```bash
composer test-coverage
```

### Environment Files

- `.env.example` - Example configuration for applications using this package
- `.env.testing` - Template for integration testing
- `.env` - Your local testing configuration (git-ignored)

## Development

### Code Style

Format code using Pint:

```bash
composer format
```

### Static Analysis

Run PHPStan analysis:

```bash
composer analyse
```

## Requirements

- PHP 8.2+
- Laravel 10.0+
- Guzzle HTTP 7.0+

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

If you discover any security vulnerabilities, please send an e-mail to [lucie@parapluie.link](mailto:lucie@parapluie.link).

## Credits

- [Lucie](https://github.com/lucie)
- [Ballchasing.com](https://ballchasing.com) for providing the API

---

Built for the Rocket League community 🚗⚽