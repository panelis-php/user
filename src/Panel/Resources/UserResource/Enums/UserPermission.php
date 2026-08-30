<?php

namespace Panelis\User\Panel\Resources\UserResource\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum UserPermission: string implements HasLabel
{
    case Browse = 'BrowseUser';

    case Read = 'ReadUser';

    case Edit = 'EditUser';

    case Create = 'CreateUser';

    case Delete = 'DeleteUser';

    case ResetPassword = 'ResetPasswordUser';

    public function getLabel(): string
    {
        return __(sprintf('user::permission.name_%s', Str::snake($this->value)));
    }
}
