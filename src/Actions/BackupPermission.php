<?php

namespace Panelis\User\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Panelis\User\Models\Permission as Model;
use Panelis\User\Panel\Resources\PermissionResource\Enums\Permission;
use Symfony\Component\HttpFoundation\Response;

class BackupPermission
{
    use AsAction;

    public function handle(): string
    {
        abort_if(! user_can(Permission::Backup), Response::HTTP_FORBIDDEN);

        $permissions = Model::query()
            ->get()
            ->mapWithKeys(function (Model $permission): array {
                return [$permission->name => [
                    'name' => $permission->name,
                    'label' => $permission->label,
                    'guard' => $permission->guard_name,
                ]];
            })
            ->toJson(JSON_PRETTY_PRINT);

        Storage::put($path = 'users/permission.json', $permissions);

        return Storage::path($path);
    }
}
