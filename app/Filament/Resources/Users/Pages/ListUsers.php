<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UsersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListUsers extends ListRecords
{
    protected static string $resource = UsersResource::class;

    public function getHeading(): string
    {
        return 'Data Pengguna';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data anda di sini';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Pengguna')->icon(Heroicon::Plus),
        ];
    }
}