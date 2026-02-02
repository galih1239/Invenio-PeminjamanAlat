<?php

namespace App\Filament\Resources\PengajuanPeminjamen\Pages;

use App\Filament\Resources\PengajuanPeminjamen\PengajuanPeminjamanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListPengajuanPeminjamen extends ListRecords
{
    protected static string $resource = PengajuanPeminjamanResource::class;
public function getHeading(): string|Htmlable
    {
        return "Pengajuan Peminjaman";
    }
    public function getSubheading(): string|Htmlable|null
    {
        return "Daftar pengajuan peminjaman barang yang telah diajukan";
    }

}
