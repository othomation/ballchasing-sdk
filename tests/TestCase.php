<?php

namespace Lucie\BallchasingLaravel\Tests;

use Lucie\BallchasingLaravel\BallchasingServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BallchasingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('ballchasing.api_key', 'test-api-key');
        config()->set('ballchasing.base_url', 'https://ballchasing.com/api');
        config()->set('ballchasing.timeout', 30);
        config()->set('ballchasing.retry_attempts', 3);
        config()->set('ballchasing.retry_delay', 500);
    }
}
