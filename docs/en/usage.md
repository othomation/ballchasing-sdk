# Ballchasing Laravel SDK - Usage Guide

## Getting an API Token

There are two ways to obtain a valid API token:

### Method 1: Web Interface (Recommended)

1. Go to [https://ballchasing.com/upload](https://ballchasing.com/upload)
2. Login via Steam (required)
3. Use the web interface to generate a new API token
4. Copy the generated token to your `.env` file

### Method 2: Programmatic Token Generation

If you need to generate tokens programmatically (e.g., for automation):

1. First, login via Steam on [https://ballchasing.com](https://ballchasing.com)
2. Extract the `ballchasing` cookie from your browser
3. Make a POST request to generate a token:

```bash
curl -X POST https://ballchasing.com/user/token \
  -H "Cookie: ballchasing=<your_cookie_value>"
```

**Note:** The cookie method is primarily for advanced use cases. For most applications, using the web interface is simpler and more secure.

## Basic Usage

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

## Available Methods

### Replays

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

### Groups

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

### Maps

```php
// Get all available maps
$maps = $ballchasing->getMaps();
// Returns: ['stadium_p' => 'DFH Stadium', 'park_p' => 'Beckwith Park', ...]
```

## Data Transfer Objects (DTOs)

The package uses DTOs for type safety and better IDE support:

```php
$replays = $ballchasing->getReplays();

foreach ($replays->replays as $replay) {
    echo $replay->id;        // string
    echo $replay->title;     // ?string (can be null)
    echo $replay->created;   // string (ISO date)
    echo $replay->duration;  // ?int (seconds)
    echo $replay->mapCode;   // ?string
}

echo $replays->count; // Total number of replays
```

## Error Handling

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

## Configuration Options

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

## Environment Files

- `.env.example` - Example configuration for applications using this package
- `.env.testing` - Template for integration testing
- `.env` - Your local testing configuration (git-ignored)

## Common Use Cases

### Tournament Management

```php
// Create a tournament group
$tournament = $ballchasing->createGroup([
    'name' => 'RLCS World Championship 2024',
    'type' => 'tournament'
]);

// Upload replays to the tournament
$replay = $ballchasing->uploadReplay('/path/to/final.replay', [
    'title' => 'Grand Final - Game 7',
    'group' => $tournament->id,
    'visibility' => 'public'
]);
```

### Player Statistics

```php
// Get replays for a specific player
$playerReplays = $ballchasing->getReplays([
    'player-name' => 'Jstn.',
    'count' => 100,
    'sort-by' => 'created',
    'sort-dir' => 'desc'
]);

foreach ($playerReplays->replays as $replay) {
    echo "Match: {$replay->title} on " . date('Y-m-d', strtotime($replay->created));
}
```

### Map Analysis

```php
// Get all available maps
$maps = $ballchasing->getMaps();

// Filter replays by specific map
$stadiumReplays = $ballchasing->getReplays([
    'map' => 'stadium_p', // DFH Stadium
    'count' => 50
]);
```