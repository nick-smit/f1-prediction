<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use NickSmit\OpenF1Api\Client\OpenF1ApiClient;
use NickSmit\OpenF1Api\Factory\OpenF1ApiClientFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(OpenF1ApiClient::class, fn (Application $application): OpenF1ApiClient => $application->make(OpenF1ApiClientFactory::class)->create(['timeout' => 30, 'connect_timeout' => 30]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
