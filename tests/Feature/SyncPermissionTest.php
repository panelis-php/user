<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Panelis\User\Actions\SyncPermission;
use Panelis\User\Panel\Resources\PermissionResource\Enums\Permission;
use Panelis\User\Panel\Resources\RoleResource\Enums\RolePermission;
use Panelis\User\Panel\Resources\UserResource\Enums\UserPermission;

uses(RefreshDatabase::class);

it('syncs permissions from Composer metadata and removes obsolete records', function (): void {
    $permissionModel = get_permission_model();

    $existing = $permissionModel::query()->create([
        'name' => UserPermission::Browse->value,
        'guard_name' => 'web',
        'label' => 'Old label',
    ]);

    $permissionModel::query()->create([
        'name' => 'ObsoletePermission',
        'guard_name' => 'web',
        'label' => 'Obsolete',
    ]);

    $result = SyncPermission::run();

    expect($result['created'])->toBeGreaterThan(0)
        ->and($result['updated'])->toBe(1)
        ->and($result['deleted'])->toBe(1);

    expect($existing->fresh()->label)->toBe(UserPermission::Browse->getLabel());
    expect($permissionModel::query()->where('name', 'ObsoletePermission')->exists())->toBeFalse();
    expect($permissionModel::query()->where('name', Permission::Sync->value)->exists())->toBeTrue();
    expect($permissionModel::query()->where('name', RolePermission::Browse->value)->exists())->toBeTrue();
});

it('reads permission enum registrations from Composer metadata', function (): void {
    $definitions = get_permission_definitions();

    expect($definitions['user'])->toContain(UserPermission::class)
        ->and($definitions['role'])->toContain(RolePermission::class)
        ->and($definitions['permission'])->toContain(Permission::class);
});
