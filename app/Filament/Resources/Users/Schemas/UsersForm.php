<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\HakAkses;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UsersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->hiddenOn('edit'),

            Select::make('role')
                ->label('Role')
                ->options([
                    HakAkses::ADMIN->value => 'Admin',
                    HakAkses::PETUGAS->value => 'Petugas',
                    HakAkses::PENGGUNA->value => 'Pengguna',
                ])
                ->required()
                ->default(HakAkses::PENGGUNA->value),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}