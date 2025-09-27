<?php

namespace Lucie\BallchasingLaravel\Tests;

use Lucie\BallchasingLaravel\Services\BallchasingClient;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected BallchasingClient $client;

    private static bool $apiConnectivityTested = false;

    protected function setUp(): void
    {
        parent::setUp();

        $apiKey = $this->getApiKey();

        if (! $this->hasValidApiKey($apiKey)) {
            $this->markTestSkipped(
                'Integration tests require a valid API key. '.
                'Copy .env.testing to .env and add your BALLCHASING_API_KEY'
            );
        }

        $this->client = new BallchasingClient(
            apiKey: $apiKey,
            timeout: 60  // Augmenter le timeout pour les tests d'intégration
        );

        // Test API connectivity only once
        if (! self::$apiConnectivityTested) {
            if (! $this->isApiKeyWorking()) {
                $this->markTestSkipped(
                    'API key appears to be invalid or API is not accessible. '.
                    'Please check your BALLCHASING_API_KEY or try again later.'
                );
            }
            self::$apiConnectivityTested = true;
        }
    }

    private function getApiKey(): string
    {
        return $_ENV['BALLCHASING_API_KEY']
            ?? getenv('BALLCHASING_API_KEY')
            ?? 'test-api-key';
    }

    private function hasValidApiKey(string $apiKey): bool
    {
        return $apiKey !== 'test-api-key' && ! empty($apiKey);
    }

    private function isApiKeyWorking(): bool
    {
        try {
            // Quick test with maps endpoint (lightweight)
            $this->client->getMaps();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
