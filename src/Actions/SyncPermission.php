<?php

namespace Panelis\User\Actions;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncPermission
{
    use AsAction;

    /**
     * @return array{created: int, updated: int, deleted: int}
     */
    public function handle(): array
    {
        $permissionNames = [];
        $created = 0;
        $updated = 0;

        foreach (get_permission_definitions() as $enums) {
            foreach ($enums as $enum) {
                if (! enum_exists($enum) || ! is_subclass_of($enum, BackedEnum::class)) {
                    continue;
                }

                foreach ($enum::cases() as $case) {
                    if (! $case instanceof HasLabel) {
                        continue;
                    }

                    $permissionNames[] = $case->value;

                    $permission = get_permission_model()::query()->firstOrNew(
                        [
                            'name' => $case->value,
                            'guard_name' => 'web',
                        ],
                    );

                    $label = $case->getLabel();
                    if ($permission->exists) {
                        $updated += $permission->getRawOriginal('label') !== $label ? 1 : 0;
                    } else {
                        $created++;
                    }

                    $permission->label = $label;
                    $permission->save();
                }
            }
        }

        if (empty($permissionNames)) {
            return ['created' => 0, 'updated' => 0, 'deleted' => 0];
        }

        $deleted = get_permission_model()::query()
            ->whereNotIn('name', array_unique($permissionNames))
            ->delete();

        return compact('created', 'updated', 'deleted');
    }
}
