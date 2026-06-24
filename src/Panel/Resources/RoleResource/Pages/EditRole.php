<?php

namespace Panelis\User\Panel\Resources\RoleResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Http\Response;
use Panelis\User\Models\Role;
use Panelis\User\Panel\Resources\RoleResource;
use Panelis\User\Panel\Resources\RoleResource\Enums\RolePermission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function authorizeAccess(): void
    {
        abort_unless(user_can(RolePermission::Edit), Response::HTTP_FORBIDDEN);
    }

    protected function getHeaderActions(): array
    {
        $role = Role::query()
            ->withCount('users')
            ->where('id', $this->data['id'])
            ->first();

        return [
            ViewAction::make()->visible(user_can(RolePermission::Read)),
            DeleteAction::make()->visible(
                user_can(RolePermission::Delete) &&
                    $role->users_count <= 0,
            ),
        ];
    }
}
