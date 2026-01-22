<?php

namespace App\Filament\Resources\Ruangans\Pages;

use App\Filament\Resources\Ruangans\RuanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ListRuangans extends ListRecords
{
    protected static string $resource = RuanganResource::class;

public function getHeading(): string|Htmlable
{
    return 'Ruangan';
}
public function getSubHeading(): string|Htmlable|null
{
    return 'Ini adalah Ruangan';
}
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label(label:'Tambah Ruangan')
            ->icon(Heroicon::Plus),
        ];
    }
}
