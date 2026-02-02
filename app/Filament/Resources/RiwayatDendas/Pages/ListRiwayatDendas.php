<?php

namespace App\Filament\Resources\RiwayatDendas\Pages;

use App\Filament\Resources\RiwayatDendas\RiwayatDendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListRiwayatDendas extends ListRecords
{
    protected static string $resource = RiwayatDendaResource::class;
    public function getHeading(): string
    {
        return 'Riwayat Denda';
    }

public function getSubHeading(): string|Htmlable|null
    {
        return "Riwayat Denda Keterlambatan";
    }
protected function getTableQuery(): Builder|Relation|null
{
 return parent::getTableQuery()
        ->where('terverifikasi', true)
        ->with([
            'peminjaman.barang',
        ]);}
}
