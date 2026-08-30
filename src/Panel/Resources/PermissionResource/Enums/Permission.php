<?php

namespace Panelis\User\Panel\Resources\PermissionResource\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum Permission: string implements HasLabel
{
    case Browse = 'BrowseUserPermission';

    case Read = 'ReadUserPermission';

    case Sync = 'SyncPermission';

    public function getLabel(): string
    {
        return __(sprintf('user::permission.name_%s', Str::snake($this->value)));
    }
}
