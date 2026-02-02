<?php

namespace App\Filament\Resources\DataPengembalians\Pages;

use App\Filament\Resources\DataPengembalians\DataPengembalianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListDataPengembalians extends ListRecords
{
    protected static string $resource = DataPengembalianResource::class;
    protected static ?int $navigationSort = 1;
    public function getTitle(): string|Htmlable
    {
        return "Data Pengembalian";
    }
    public function getSubheading(): string|Htmlable|null
    {
        return "Daftar Pengembalian Barang Yang Telah Dipinjam.";
    }
}