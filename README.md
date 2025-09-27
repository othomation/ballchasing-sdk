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

## Testing

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

### Versioning

This package follows [Semantic Versioning](https://semver.org/). Use the built-in scripts to manage versions:

```bash
# Show current version
composer version

# Bump patch version (0.1.0 -> 0.1.1)
composer version:patch

# Bump minor version (0.1.0 -> 0.2.0)
composer version:minor

# Bump major version (0.1.0 -> 1.0.0)
composer version:major

# Run all checks before release (tests, format, analyse)
composer release
```

**Note:** Since this is a beta package (0.x.x), the API may change between minor versions until we reach 1.0.0.

## Documentation

📖 **Full documentation is available in multiple languages:**

- **English**: [docs/en/usage.md](docs/en/usage.md)
- **Français**: [docs/fr/usage.md](docs/fr/usage.md)

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

- [Lucie](https://github.com/othomation)
- [Ballchasing.com](https://ballchasing.com) for providing the API

---

Built for the Rocket League community 🚗⚽