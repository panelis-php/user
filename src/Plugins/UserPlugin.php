<?php

declare(strict_types=1);

namespace Panelis\User\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Panelis\User\Panel\Resources\PermissionResource;
use Panelis\User\Panel\Resources\RoleResource;
use Panelis\User\Panel\Resources\UserResource;

class UserPlugin implements Plugin
{
    public function getId(): string
    {
        return 'user';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RoleResource::class,
            PermissionResource::class,
            UserResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
