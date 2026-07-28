<?php

namespace Panelis\User\Actions;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class SeedPermission
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        foreach (config('permission.enums') as $enum) {
            foreach ($enum::cases() as $case) {
                $key = Str::snake($case->value);
                get_permission_model()::query()
                    ->updateOrCreate(['name' => $case->value], [
                        'guard_name' => 'web',
                        'label' => "user.permission.name_{$key}",
                    ]);
            }
        }
    }
}
