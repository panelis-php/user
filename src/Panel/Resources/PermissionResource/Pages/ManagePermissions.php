<?php

namespace Panelis\User\Panel\Resources\PermissionResource\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Http\Response;
use Panelis\User\Actions\SyncPermission;
use Panelis\User\Panel\Resources\PermissionResource;
use Panelis\User\Panel\Resources\PermissionResource\Enums\Permission;

class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_permission')
                ->visible(user_can(Permission::Sync))
                ->label(__('user::permission.sync'))
                ->requiresConfirmation()
                ->action(function (): void {
                    $result = SyncPermission::run();

                    Notification::make()
                        ->title(__('user::permission.synced'))
                        ->body(__('user::permission.sync_summary', $result))
                        ->success()
                        ->send();
                }),

        ];
    }

    protected function authorizeAccess(): void
    {
        abort_unless(
            user_can(Permission::Browse) || user_can(Permission::Read),
            Response::HTTP_FORBIDDEN,
        );
    }
}
