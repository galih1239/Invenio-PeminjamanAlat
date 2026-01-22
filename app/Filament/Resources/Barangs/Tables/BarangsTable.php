<?php

namespace App\Filament\Resources\Barangs\Tables;

use App\Enums\KondisiBarang;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columnManager(false)
            ->emptyStateHeading('Tidak ada Data Barang')
            ->emptyStateDescription('Mulai tambahkan barang ke sistem anda')
            ->columns([
                ImageColumn::make('foto')
                    ->extraImgAttributes([
                        'alt' => 'Logo',
                        'loading' => 'lazy'
                    ])
                    ->label('Foto')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->toggleable(),

                TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode barang disalin!')
                    ->weight('bold'),

                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.name')
                    ->label('Lokasi Ruang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kondisi')
                    ->badge()
                    ->label('Kondisi')
                    ->formatStateUsing(fn(KondisiBarang $state): string => $state->label())
                    ->color(fn(KondisiBarang $state): string => match ($state) {
                        KondisiBarang::BAIK => 'success',
                        KondisiBarang::PERBAIKAN => 'warning',
                        KondisiBarang::RUSAK => 'danger',
                    })
                    ->icon(fn(KondisiBarang $state): string => match ($state) {
                        KondisiBarang::BAIK => 'heroicon-o-check-circle',
                        KondisiBarang::RUSAK => 'heroicon-o-x-circle',
                        KondisiBarang::PERBAIKAN => 'heroicon-o-wrench',
                    }),

                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('kondisi')
                    ->label('Kondisi')
                    ->options([
                        'baik' => 'Baik',
                        'rusak' => 'Rusak',
                        'perbaikan' => 'Perbaikan',
                    ])
                    ->multiple(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->color('warning'),
                    ViewAction::make()->color(Color::Violet)
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}