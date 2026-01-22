<?php

namespace App\Filament\Resources\BarangTersedias\Pages;

use App\Filament\Resources\BarangTersedias\BarangTersediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListBarangTersedias extends ListRecords
{
    protected static string $resource = BarangTersediaResource::class;
public function getHeading(): string
    {
        return 'Barang Tersedia';
    }

    public function getSubheading(): ?string
    {
        return 'Daftar barang yang saat ini tersedia untuk dipinjam';
    }
    
}
