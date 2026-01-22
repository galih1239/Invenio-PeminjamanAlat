<?php

namespace App\Filament\Resources\KategoriBarangs\Pages;

use App\Filament\Resources\KategoriBarangs\KategoriBarangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color as ColorsColor;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Symfony\Component\Console\Color;

class ListKategoriBarangs extends ListRecords
{
    protected static string $resource = KategoriBarangResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Kategori';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Ini deskripsi singkat kategori';
    }

    protected function getHeaderActions(): array
    {
        return [
               CreateAction::make()->label('Tambah Kategori')->icon(Heroicon::Plus),
        ];
    }
}
