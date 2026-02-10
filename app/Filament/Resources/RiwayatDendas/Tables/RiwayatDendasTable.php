<?php

namespace App\Filament\Resources\RiwayatDendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RiwayatDendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Riwayat masih kosong')
            ->emptyStateDescription('Belum ada data riwayat denda.')
            ->columns([
                TextColumn::make('barang.name')
                ->label('Nama Barang')
                ->getStateUsing(fn ($record) =>
                $record->peminjaman?->barang?->name ?? '-'
    )
                ->searchable(),

                TextColumn::make('barang.kode')
                ->label('Kode Barang')
                ->badge()
                ->getStateUsing(fn ($record) =>
                 $record->peminjaman?->barang?->kode_barang ?? '-'
    ),


                TextColumn::make('total_bayar')
    ->label('Total Bayar')
    ->money('IDR')
    ->formatStateUsing(fn ($state) => abs($state)),


                TextColumn::make('terverifikasi')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Terverifikasi' : 'Menunggu')
                    ->color(fn (bool $state) => $state ? 'success' : 'warning'),

                TextColumn::make('path_bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->icon('heroicon-o-document-arrow-down')
                    ->formatStateUsing(fn ($state) => $state ? 'Lihat' : '-')
                    ->url(fn ($state) => $state ? asset('storage/' . $state) : null)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
