<?php

namespace Panelis\User\Actions;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Panelis\User\Models\Permission;
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
                Permission::query()
                    ->updateOrCreate(['name' => $case->value], [
                        'guard_name' => 'web',
                        'label' => "user.permission.name_{$key}",
                    ]);
            }
        }
    }
}
