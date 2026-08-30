<?php

namespace Panelis\User\Providers;

use Illuminate\Support\ServiceProvider;
use Panelis\User\Commands\SyncPermissionsCommand;

class UserServiceProvider extends ServiceProvider
{
    private const string NAMESPACE = 'user';

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', self::NAMESPACE);

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncPermissionsCommand::class,
            ]);
        }

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
