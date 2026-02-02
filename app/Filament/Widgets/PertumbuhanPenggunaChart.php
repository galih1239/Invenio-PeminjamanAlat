<?php

namespace App\Filament\Widgets;

use App\Enums\HakAkses;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PertumbuhanPenggunaChart extends ChartWidget
{
 protected ?string $heading = "Pertumbuhan Pengguna";

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::user()?->role == HakAkses::ADMIN;
    }

    protected function getData(): array
{
    $data = User::selectRaw("
            COUNT(*) as total,
            TO_CHAR(created_at, 'Mon YYYY') as bulan,
            MIN(created_at) as sort_date
        ")
        ->whereNull('deleted_at')
        ->groupBy('bulan')
        ->orderBy('sort_date')
        ->get();

    return [
        'datasets' => [
            [
                'label' => 'Jumlah Pengguna',
                'data' => $data->pluck('total'),
            ],
        ],
        'labels' => $data->pluck('bulan'),
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