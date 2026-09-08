<?php

namespace Panelis\User\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Panelis\User\Commands\SyncPermissionsCommand;

class UserServiceProvider extends ServiceProvider
{
    private const string NAMESPACE = 'user';

    public function boot(): void
    {
        $this->syncActivityLoggingSetting();

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
        Relation::morphMap([
            'user' => get_user_model(),
        ], merge: false);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/user.php',
            self::NAMESPACE,
        );
    }

    private function syncActivityLoggingSetting(): void
    {
        $settingClass = 'Panelis\\Setting\\Models\\Setting';

        if (class_exists($settingClass) && config()->has('activitylog.enabled')) {
            config()->set('activitylog.enabled', $settingClass::get('activity.enabled', config('activitylog.enabled')));
        }
    }
}
