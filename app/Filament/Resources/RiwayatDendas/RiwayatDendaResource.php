<?php

namespace App\Filament\Resources\RiwayatDendas;

use App\Enums\HakAkses;
use App\Enums\StatusPeminjaman;
use App\Filament\Resources\RiwayatDendas\Pages\CreateRiwayatDenda;
use App\Filament\Resources\RiwayatDendas\Pages\EditRiwayatDenda;
use App\Filament\Resources\RiwayatDendas\Pages\ListRiwayatDendas;
use App\Filament\Resources\RiwayatDendas\Pages\ViewRiwayatDenda;
use App\Filament\Resources\RiwayatDendas\Schemas\RiwayatDendaForm;
use App\Filament\Resources\RiwayatDendas\Schemas\RiwayatDendaInfolist;
use App\Filament\Resources\RiwayatDendas\Tables\RiwayatDendasTable;
use App\Models\RiwayatDenda;
use App\Models\VerifikasiPengembalian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class RiwayatDendaResource extends Resource
{
    protected static ?string $model = VerifikasiPengembalian::class;
     public static function canViewAny(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|BackedEnum|null $activenavigationIcon = Heroicon::Banknotes;

     protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Riwayat Denda';
    protected static ?string $breadcrumb = 'Riwayat Denda';
     protected static string|UnitEnum|null $navigationGroup = "Riwayat";

public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('terverifikasi', true);

    }

    public static function form(Schema $schema): Schema
    {
        return RiwayatDendaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RiwayatDendaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RiwayatDendasTable::configure($table);
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
            'index' => ListRiwayatDendas::route('/'),
            'create' => CreateRiwayatDenda::route('/create'),
            'view' => ViewRiwayatDenda::route('/{record}'),
            'edit' => EditRiwayatDenda::route('/{record}/edit'),
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
