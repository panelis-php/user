<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
