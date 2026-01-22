<?php

namespace App\Filament\Resources\KategoriBarangs\Pages;

use App\Filament\Resources\KategoriBarangs\KategoriBarangResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;



class CreateKategoriBarang extends CreateRecord
{

    public function getHeading(): string|Htmlable
    {
        return 'Tambah Kategori';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Ini deskripsi singkat tambah kategori';
    }

    protected static string $resource = KategoriBarangResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan'),
            $this->getCancelFormAction()->label('Batal')->color('danger'),
        ];
    }
}
