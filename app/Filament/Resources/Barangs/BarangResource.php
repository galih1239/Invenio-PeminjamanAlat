<?php

namespace App\Filament\Resources\Barangs;

use App\Enums\HakAkses;
use App\Filament\Resources\Barangs\Pages\CreateBarang;
use App\Filament\Resources\Barangs\Pages\EditBarang;
use App\Filament\Resources\Barangs\Pages\ListBarangs;
use App\Filament\Resources\Barangs\Schemas\BarangForm;
use App\Filament\Resources\Barangs\Tables\BarangsTable;
use App\Models\Barang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    public static function canViewAny(): bool
    {
        return Auth::user()?->role === HakAkses::ADMIN;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === HakAkses::ADMIN;
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'name';
    protected static string|UnitEnum|null $navigationGroup = "Inventaris";

    protected static ?string $navigationLabel = 'Data Barang';
    protected static ?string $breadcrumb = 'Data Barang';
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Cube;
    public static function form(Schema $schema): Schema
    {
        return BarangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BarangsTable::configure($table);
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
            'index' => ListBarangs::route('/'),
            'create' => CreateBarang::route('/create'),
            'edit' => EditBarang::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // 1. Subquery: Filter deleted_at dilakukan di sini
        $subQuery = Barang::query()
            ->selectRaw('
            MIN(id) as id, 
            name, 
            category_id, 
            room_id, 
            MAX(foto) as foto,
            COUNT(*) as jumlah_barang
        ')
            ->whereNull('deleted_at') // Filter sudah di sini
            ->groupBy('name', 'category_id', 'room_id');

        // 2. Query Utama: Gunakan withTrashed() agar Laravel tidak menambah 'where deleted_at is null' lagi
        return Barang::query()
            ->fromSub($subQuery, 'barangs')
            ->withTrashed() // KUNCI UTAMA: Agar query luar tidak mencari kolom deleted_at
            ->with(['category', 'room']); // Agar relasi tetap muncul
    }


    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
