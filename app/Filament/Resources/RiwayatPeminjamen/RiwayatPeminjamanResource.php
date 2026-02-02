<?php

namespace App\Filament\Resources\RiwayatPeminjamen;

use App\Enums\HakAkses;
use App\Enums\StatusPeminjaman;
use App\Filament\Resources\RiwayatPeminjamen\Pages\CreateRiwayatPeminjaman;
use App\Filament\Resources\RiwayatPeminjamen\Pages\EditRiwayatPeminjaman;
use App\Filament\Resources\RiwayatPeminjamen\Pages\ListRiwayatPeminjamen;
use App\Filament\Resources\RiwayatPeminjamen\Schemas\RiwayatPeminjamanForm;
use App\Filament\Resources\RiwayatPeminjamen\Tables\RiwayatPeminjamenTable;
use App\Models\PeminjamanBarang;
use App\Models\RiwayatPeminjaman;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class RiwayatPeminjamanResource extends Resource
{
    protected static ?string $model = PeminjamanBarang::class;
     public static function canViewAny(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Clock;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Riwayat Peminjaman';
    protected static ?string $breadcrumb = 'Riwayat Peminjaman';
     protected static string|UnitEnum|null $navigationGroup = "Riwayat";

    public static function form(Schema $schema): Schema
    {
        return RiwayatPeminjamanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RiwayatPeminjamenTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('status', StatusPeminjaman::inactive());
    }
    public static function getPages(): array
    {
        return [
            'index' => ListRiwayatPeminjamen::route('/'),
            'create' => CreateRiwayatPeminjaman::route('/create'),
            'edit' => EditRiwayatPeminjaman::route('/{record}/edit'),
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
