<?php

namespace App\Filament\Widgets;

use App\Enums\HakAkses;
use App\Enums\StatusPeminjaman;
use App\Models\PeminjamanBarang;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PeminjamStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return Auth::user()?->role == HakAkses::PENGGUNA;
    }

    protected function getStats(): array
    {
        $userId = Auth::id();
        return [
    
            Stat::make(
                'Sedang Dipinjam',
                PeminjamanBarang::where('peminjaman_id', $userId)
                ->where('status', StatusPeminjaman::DIPINJAM)
                ->count()
            )
                ->description('Barang sedang dipinjam')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle, IconPosition::Before)
                ->color('success'),

                 Stat::make(
                'Menunggu Persetujuan',
                PeminjamanBarang::where('peminjaman_id', $userId)
                ->where('status', StatusPeminjaman::BELUM_DISETUJUI)
                ->count()
            )
                ->description('Yang belum disetujui')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle, IconPosition::Before)
                ->color('warning'),

                Stat::make(
                'Terlambat',
                PeminjamanBarang::where('peminjaman_id', $userId)
                ->where('status', StatusPeminjaman::TERLAMBAT)
                ->count()
            )
                ->description('Terlambat Mengembalikan')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle, IconPosition::Before)
                ->color('danger'),

        ];
    }
}