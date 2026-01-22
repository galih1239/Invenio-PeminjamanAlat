<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    public function getHeading(): string
    {
        return 'Data Barang';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data barang anda di sini';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Barang')->icon(Heroicon::Plus)->color(Color::Violet),
        ];
    }
}