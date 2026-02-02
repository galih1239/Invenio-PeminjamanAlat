<?php

namespace App\Filament\Resources\PeminjamanBarangs\Schemas;

use App\Enums\KondisiBarang;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PeminjamanBarangForm
{
    public static function configure(Schema $schema): Schema
    {
      return $schema
      ->components([
        Section::make('Informasi Peminjaman')
        ->description(
            fn(string $operation):string=> $operation === 'create' ?'Lengkapi data peminjaman barang' :'Pastikan data peminjaman sudah benar')
            ->components([
                Select::make('barang_id')
                ->label('Barang')
                ->relationship(
                name: 'barang', 
                titleAttribute : 'name',
                modifyQueryUsing : fn (Builder $query) =>
                $query
                ->where('kondisi', KondisiBarang::BAIK->value)
                ->with('category')
                )
            ->searchable(['kode_barang', 'id'])
            ->preload()
            ->getOptionLabelFromRecordUsing(
                fn($record)=>
                "{$record->name} | {$record->kode_barang} | {$record->category?->name} "
            )
            ->required(),
            DatePicker::make('tanggal_pinjam')
            ->label('Tanggal Pinjam')
            ->required()
            ->minDate(now()->startOfDay())
            ->maxDate(now()->endOfYear()),


            Textarea::make('keperluan')
            ->label('Keperluan')
            ->helperText('opsional, deskripsikan keperluan anda dalam meminjam barang')
            ->rows(3)
            ])
      ]);
    }
}
