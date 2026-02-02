<?php

namespace App\Filament\Widgets;

use App\Enums\HakAkses;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PetugasPeminjamanChart extends ChartWidget
{
    protected ?string $heading = "Statistik Peminjaman Bulanan Anda";

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::user()?->role == HakAkses::PETUGAS;
    }

    protected function getData(): array
    {
        $userId = Auth::id();
        $year = Carbon::now()->year;

        $data = PeminjamanBarang::query()
        ->selectRaw('EXTRACT(MONTH FROM tanggal_disetujui) AS bulan, COUNT(*) AS total')
        ->where('petugas_id', $userId)
        ->whereYear('tanggal_disetujui', $year)
        ->whereNotNull('tanggal_disetujui')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan')
        ->toArray();

        $labels = [];
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create($year, $m, 1)->format('M');
            $values[] = $data[$m] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Peminjaman Disetujui',
                    'data' => $values,
                    'backgroundColor' => '#4f46e5',
                    'fill' => true,
                ],
            ],
        ];
    }
public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getType(): string
    {
        return 'line';
    }
}