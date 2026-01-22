<?php

namespace App\Filament\Resources\Ruangans\Pages;

use App\Filament\Resources\Ruangans\RuanganResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateRuangan extends CreateRecord
{
    protected static string $resource = RuanganResource::class;
    public function getHeading(): string|Htmlable
    {
        return 'Tambah Ruangan';
    }
    // public function getSubHeading(): string|Htmlable|null
    // {
    //     return 'Lengkapi ruangan';
    // }
    public function getRedirectUrl(): string
    {
        return $this->getResource() :: getUrl('index');
    }
    public function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label(label: 'Simpan'),
            $this->getCancelFormAction()->label(label: 'Batal')
            ->color(color: 'danger'),
        ];
    }
}
