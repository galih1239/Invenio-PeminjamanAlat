<?php

namespace App\Filament\Resources\BarangTersedias;

use App\Enums\KondisiBarang;
use App\Enums\StatusPeminjaman;
use App\Filament\Resources\BarangTersedias\Pages\CreateBarangTersedia;
use App\Filament\Resources\BarangTersedias\Pages\EditBarangTersedia;
use App\Filament\Resources\BarangTersedias\Pages\ListBarangTersedias;
use App\Filament\Resources\BarangTersedias\Pages\ViewBarangTersedia;
use App\Filament\Resources\BarangTersedias\Schemas\BarangTersediaForm;
use App\Filament\Resources\BarangTersedias\Schemas\BarangTersediaInfolist;
use App\Filament\Resources\BarangTersedias\Tables\BarangTersediasTable;
use App\Models\BarangTersedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Enums\HakAkses;
use App\Models\Barang;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;


class BarangTersediaResource extends Resource
{
    public static function canViewAny(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === HakAkses::PENGGUNA;
    }


    protected static ?string $model = Barang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "Aktivitas";

    

    protected static ?string $recordTitleAttribute = 'name';


     public static function getEloquentQuery(): Builder
    {
         $subQuery = Barang::query()
        ->selectRaw('
            MIN(id) as id,
            name,
            category_id,
            room_id,
            MAX(foto) as foto,
            COUNT(*) as jumlah_barang
        ')
        ->whereNull('deleted_at')
        ->where('kondisi', KondisiBarang::BAIK)
        ->whereDoesntHave('peminjamanBarangs', function (Builder $query) {
            $query->where('status', '!=', StatusPeminjaman::DIKEMBALIKAN);
        })
        ->groupBy('name', 'category_id', 'room_id');

    return Barang::query()
        ->fromSub($subQuery, 'barangs')
        ->withTrashed()
        ->with(['category', 'room']);
}


    public static function form(Schema $schema): Schema
    {
        return BarangTersediaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BarangTersediaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BarangTersediasTable::configure($table);
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
            'index' => ListBarangTersedias::route('/'),
            'create' => CreateBarangTersedia::route('/create'),
            'view' => ViewBarangTersedia::route('/{record}'),
            'edit' => EditBarangTersedia::route('/{record}/edit'),
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