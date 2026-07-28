<?php

namespace Panelis\User\Actions;

use Filament\Auth\Notifications\ResetPassword;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class SendResetPasswordLink
{
    use AsAction;

    public function handle(Model $user): void
    {
        $token = app('auth.password.broker')->createToken($user);
        $notification = new ResetPassword($token);
        $notification->url = Filament::getResetPasswordUrl($token, $user);

        $user->notify($notification);
    }
}
