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
