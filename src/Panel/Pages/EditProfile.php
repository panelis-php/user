<?php

namespace Panelis\User\Panel\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('user::user.email'))
            ->email()
            ->required()
            ->maxLength(100)
            ->unique(ignoreRecord: true);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('user::user.account'))
                ->schema([
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ]),

        ]);
    }
}
