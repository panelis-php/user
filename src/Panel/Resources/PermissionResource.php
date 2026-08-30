<?php

namespace Panelis\User\Panel\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Panelis\User\Panel\Resources\PermissionResource\Pages\ManagePermissions;

class PermissionResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static bool $isScopedToTenant = false;

    public static function getModel(): string
    {
        return get_permission_model();
    }

    public static function getLabel(): ?string
    {
        return __('user::permission.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('user::permission.navigation');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('user::user.navigation');
    }

    public static function canAccess(): bool
    {
        return user_can(PermissionResource\Enums\Permission::Browse);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label(__('user::permission.name'))
                    ->disabledOn('edit')
                    ->required()
                    ->unique(ignorable: $schema->getRecord())
                    ->minLength(3)
                    ->maxLength(30),

                TextInput::make('guard_name')
                    ->label(__('user::permission.guard_name'))
                    ->disabledOn('edit')
                    ->default('web')
                    ->datalist(['web', 'api'])
                    ->required(),

                TextEntry::make('label')
                    ->label(__('user::permission.name'))
                    ->visibleOn('edit')
                    ->state(fn (Model $record): string => $record->label),
            ]);
    }

    public static function table(Table $table): Table
    {
        $permissionGroups = collect(get_permission_definitions())
            ->flatMap(fn (array $enums, string $group): array => collect($enums)
                ->flatMap(fn (string $enum): array => enum_exists($enum)
                    ? collect($enum::cases())->mapWithKeys(fn ($case): array => [$case->value => $group])->all()
                    : [])
                ->all())
            ->all();

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($permissionGroups): void {
                $column = $query->getModel()->qualifyColumn('name');
                $cases = [];
                $bindings = [];

                foreach (collect($permissionGroups)->mapToGroups(fn (string $group, string $name): array => [$group => $name]) as $group => $names) {
                    $cases[] = sprintf(
                        'WHEN %s IN (%s) THEN ?',
                        $column,
                        $names->map(fn (): string => '?')->implode(', '),
                    );
                    $bindings = [...$bindings, ...$names->all(), $group];
                }

                $expression = $cases ? 'CASE '.implode(' ', $cases).' ELSE ? END' : '?';

                $query->selectRaw("{$query->getModel()->getTable()}.*, {$expression} AS permission_group", [...$bindings, 'other']);
            })
            ->columns([
                TextColumn::make('label')
                    ->label(__('user::permission.name'))
                    ->searchable(['name', 'label', 'description'])
                    ->sortable()
                    ->description(fn (?Model $record): string => $record?->description ?? ''),

                TextColumn::make('guard_name')
                    ->label(__('user::permission.guard_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::makeSinceDate('updated_at', __('ui.updated_at')),

                TextColumn::makeSinceDate('created_at', __('ui.created_at')),
            ])
            ->groups([
                Group::make('permission_group')
                    ->label(__('user::permission.group'))
                    ->getTitleFromRecordUsing(fn (Model $record): string => Str::headline($record->permission_group)),
            ])
            ->defaultGroup('permission_group')
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePermissions::route('/'),
        ];
    }
}
