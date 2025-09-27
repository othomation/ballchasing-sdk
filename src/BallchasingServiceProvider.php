<?php

namespace Lucie\BallchasingLaravel;

use Illuminate\Support\ServiceProvider;
use Lucie\BallchasingLaravel\Contracts\BallchasingClientInterface;
use Lucie\BallchasingLaravel\Services\BallchasingClient;

class BallchasingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ballchasing.php',
            'ballchasing'
        );

        $this->app->singleton(BallchasingClientInterface::class, function ($app) {
            return new BallchasingClient(
                apiKey: config('ballchasing.api_key'),
                baseUrl: config('ballchasing.base_url'),
                timeout: config('ballchasing.timeout'),
                retryAttempts: config('ballchasing.retry_attempts'),
                retryDelay: config('ballchasing.retry_delay')
            );
        });

        $this->app->alias(BallchasingClientInterface::class, 'ballchasing');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ballchasing.php' => config_path('ballchasing.php'),
            ], 'ballchasing-config');
        }
    }
}
