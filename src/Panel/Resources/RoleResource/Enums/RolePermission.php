<?php

namespace Panelis\User\Panel\Resources\RoleResource\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum RolePermission: string implements HasLabel
{
    case Browse = 'BrowseUserRole';

    case Read = 'ReadUserRole';

    case Edit = 'EditUserRole';

    case Create = 'CreateUserRole';

    case Delete = 'DeleteUserRole';

    public function getLabel(): string
    {
        return __(sprintf('user::permission.name_%s', Str::snake($this->value)));
    }
}
