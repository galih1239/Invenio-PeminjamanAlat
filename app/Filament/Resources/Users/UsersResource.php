<?php

namespace App\Filament\Resources\Users;

use App\Enums\HakAkses;
use App\Filament\Resources\Users\Pages\CreateUsers;
use App\Filament\Resources\Users\Pages\EditUsers;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUsers;
use App\Filament\Resources\Users\Schemas\UsersForm;
use App\Filament\Resources\Users\Schemas\UsersInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;
    public static function canViewAny(): bool
    {
        return Auth::user()?->role === HakAkses::ADMIN;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === HakAkses::ADMIN;
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
 protected static string|UnitEnum|null $navigationGroup = "Master Akun";

    protected static ?string $recordTitleAttribute = 'name';
protected static ?string $navigationLabel = 'Data Pengguna';
    protected static ?string $breadcrumb = 'Data Pengguna';
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Users;
    public static function form(Schema $schema): Schema
    {
        return UsersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UsersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUsers::route('/create'),
            'view' => ViewUsers::route('/{record}'),
            'edit' => EditUsers::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
