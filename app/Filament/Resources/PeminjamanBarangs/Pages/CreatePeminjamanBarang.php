<?php

namespace App\Filament\Resources\PeminjamanBarangs\Pages;

use App\Filament\Resources\PeminjamanBarangs\PeminjamanBarangResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Color;

class CreatePeminjamanBarang extends CreateRecord
{
    protected static string $resource = PeminjamanBarangResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['peminjaman_id'] = Auth::user()->id;
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
            ->label('Simpan'),
            $this->getCancelFormAction()
            ->label('Batal')
            ->color('danger')

        ];
    }
}