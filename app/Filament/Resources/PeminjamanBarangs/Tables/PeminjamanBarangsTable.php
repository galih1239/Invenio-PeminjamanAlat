<?php

namespace App\Filament\Resources\PeminjamanBarangs\Tables;

use App\Enums\StatusPeminjaman;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Symfony\Component\Console\Color;

class PeminjamanBarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->emptyStateHeading('Belum ada ajuan peminjaman barang')
            ->emptyStateDescription('Silahkan ajukan peminjaman barang yang tersedia')
            ->columns([
                ImageColumn::make('barang.foto')
                ->extraImgAttributes([
                    'alt' => 'Logo',
                    'loading' => 'Lazy'
                ])
                ->label('Foto')
                ->square()
                ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('barang.kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable()->weight('bold'),

                TextColumn::make('barang.name')
                    ->label('Nama Barang')
                    ->sortable()->weight('bold'),

                TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('tanggal_disetujui')
                    ->label('Tanggal Disetujui')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('tanggal_kembali')
                    ->label('Tanggal Kembali')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(StatusPeminjaman $state) => $state->color())
                    ->formatStateUsing(fn(StatusPeminjaman $state) => $state->label())
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                Action::make('kembalikan')->label('Kembalikan')
                ->button()
               
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc')
            ->striped();
    }
    }
