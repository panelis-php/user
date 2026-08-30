<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Panelis\User\Models\Permission;
use Panelis\User\Models\Role;
use Panelis\User\Models\User;

if (! function_exists('user_can')) {
    function user_can(BackedEnum $ability, ?Model $model = null, ?string $field = null): bool
    {
        $authorized = Auth::user()->can($ability->value);

        if (! empty($model)) {
            if (! empty($field)) {
                return $model->{$field} == Auth::id() && $authorized;
            }

            return $model->user_id == Auth::id() && $authorized;
        }

        return $authorized;
    }
}

if (! function_exists('user_cannot')) {
    function user_cannot(BackedEnum $ability, ?Model $model = null, ?string $field = null): bool
    {
        return ! user_can($ability, $model, $field);
    }
}

if (! function_exists('get_user_model')) {
    function get_user_model(): string
    {
        return config('user.models.user', User::class);
    }
}

if (! function_exists('get_role_model')) {
    function get_role_model(): string
    {
        return config('user.models.role', Role::class);
    }
}

if (! function_exists('get_permission_model')) {
    function get_permission_model(): string
    {
        return config('user.models.permission', Permission::class);
    }
}

if (! function_exists('get_permission_definitions')) {
    /**
     * @return array<string, array<class-string<BackedEnum>>>
     */
    function get_permission_definitions(): array
    {
        $installedPath = base_path('vendor/composer/installed.json');
        if (! is_file($installedPath)) {
            $installedPath = __DIR__.'/../vendor/composer/installed.json';
        }

        if (! is_file($installedPath)) {
            return [];
        }

        $installed = json_decode(file_get_contents($installedPath), true);
        $packages = array_merge($installed['packages'] ?? [], $installed['packages-dev'] ?? []);

        // The package itself is not listed in Composer's installed metadata
        // when tests are run from this repository instead of as a dependency.
        $packageComposerPath = __DIR__.'/../composer.json';
        if (is_file($packageComposerPath)) {
            $packageComposer = json_decode(file_get_contents($packageComposerPath), true);
            $packages[] = $packageComposer + ['name' => 'panelis-php/user'];
        }
        $definitions = [];

        foreach ($packages as $package) {
            $packageComposerPath = base_path('vendor/'.$package['name'].'/composer.json');
            if (! is_file($packageComposerPath) && $package['name'] === 'panelis-php/user') {
                $packageComposerPath = __DIR__.'/../composer.json';
            }
            $packageComposer = is_file($packageComposerPath)
                ? json_decode(file_get_contents($packageComposerPath), true)
                : [];
            $permissions = data_get($packageComposer, 'extra.panelis.permissions')
                ?? data_get($package, 'extra.panelis.permissions', []);

            if (array_is_list($permissions)) {
                $group = str($package['name'] ?? '')->afterLast('/')->toString();

                foreach ($permissions as $enum) {
                    $definitions[$group ?: '_legacy'][] = $enum;
                }

                continue;
            }

            foreach ($permissions as $group => $enum) {
                $definitions[$group][] = $enum;
            }
        }

        return array_map(
            fn (array $enums): array => array_values(array_unique($enums)),
            $definitions,
        );
    }
}
