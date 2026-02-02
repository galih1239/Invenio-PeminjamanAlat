<?php

namespace App\Filament\Resources\RiwayatPeminjamen\Pages;

use App\Filament\Exports\RiwayatPeminjamanExporter;
use App\Filament\Resources\RiwayatPeminjamen\RiwayatPeminjamanResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ListRiwayatPeminjamen extends ListRecords
{
    protected static string $resource = RiwayatPeminjamanResource::class;
    
public function getSubHeading(): string|Htmlable|null
    {
        return "Riwayat peminjaman barang yang sudah dipinjam";
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->icon(Heroicon::Printer)
            ->label('Eksport Laporan')
            ->exporter(RiwayatPeminjamanExporter::class)
        ];
    }
    
}
