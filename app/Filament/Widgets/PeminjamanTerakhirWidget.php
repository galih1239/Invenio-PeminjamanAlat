<?php

namespace App\Filament\Widgets;

use App\Enums\HakAkses;
use App\Enums\StatusPeminjaman;
use App\Models\PeminjamanBarang as ModelsPeminjamanBarang;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PeminjamanBarang;

class PeminjamanTerakhirWidget extends TableWidget
{
    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
    public static function canView(): bool
    {
        return Auth::user()?->role == HakAkses::PENGGUNA;
    }

    public function table(Table $table): Table
    
    {
        
        

        return $table
            ->query(fn (): Builder => ModelsPeminjamanBarang::query())
            ->columns([
                 TextColumn::make('barang.name')
                ->label('Nama Barang')
                ->formatStateUsing(fn($state) => $state ?? '-'),

            TextColumn::make('tanggal_pinjam')
                ->label('Tanggal Pinjam')
                ->formatStateUsing(
                    fn($state) =>
                    $state ? Carbon::parse($state)->format('d-m-Y') : '-'
                ),

            TextColumn::make('tanggal_kembali')
                ->label('Tanggal Kembali')
                ->formatStateUsing(
                    fn($state) =>
                    $state ? Carbon::parse($state)->format('d-m-Y') : '-'
                ),

            TextColumn::make('status')
                ->label('Status')
                ->color(fn(StatusPeminjaman $state) => $state->color())
                ->formatStateUsing(fn($state) => $state?->value ?? '-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
            
    }
}
