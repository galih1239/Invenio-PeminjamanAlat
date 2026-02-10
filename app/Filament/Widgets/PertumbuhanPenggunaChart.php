<?php

namespace App\Filament\Widgets;

use App\Enums\HakAkses;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PertumbuhanPenggunaChart extends ChartWidget
{
    protected ?string $heading = 'Pertumbuhan Pengguna';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::user()?->role === HakAkses::ADMIN;
    }

    protected function getData(): array
    {
        $data = User::query()
            ->selectRaw("
                COUNT(*) as total,
                DATE_FORMAT(created_at, '%b %Y') as bulan,
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
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('bulan')->toArray(),
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
