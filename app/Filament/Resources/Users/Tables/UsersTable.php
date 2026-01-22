<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\HakAkses;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_pict')
                    ->circular()
                    ->label('Foto')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        return $record->profile_pict ?? 'storage/profile_pict/default_pp.jpeg';
                    })
                    ->defaultImageUrl(asset('storage/profile_pict/default_pp.jpeg')),

                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama')
                    ->copyable()
                    ->copyMessage('Nama pengguna disalin!')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email pengguna disalin!')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Hak Akses')
                    ->badge()
                    ->sortable()
                    ->color(fn(HakAkses $state): string => match ($state) {
                        HakAkses::ADMIN => 'success',
                        HakAkses::PETUGAS => 'warning',
                        HakAkses::PENGGUNA => 'danger',
                    })
                    ->formatStateUsing(fn(HakAkses $state): string => $state->label()),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Hak Akses')
                    ->options(HakAkses::class),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->color('warning'),
                    ViewAction::make()->color(Color::Indigo),
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