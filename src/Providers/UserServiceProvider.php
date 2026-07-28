<?php

namespace Panelis\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    private const string NAMESPACE = 'user';

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', self::NAMESPACE);

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/user.php' => config_path('user.php'),
        ], 'user-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/user.php',
            self::NAMESPACE,
        );
    }
}
