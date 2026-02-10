<?php

namespace App\Filament\Resources\Barangs\Tables;

use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Models\Barang;
use Filament\Actions\Action;

class BarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->defaultSort('name', 'asc')
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),

                TextColumn::make('room.name')
                    ->label('Ruangan'),

                TextColumn::make('jumlah_barang')
                    ->label('Jumlah')
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Action::make('view_list')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn(Barang $record) => "Daftar Barang: {$record->name}")
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->infolist([
                        RepeatableEntry::make('item_details')
                            ->label('Detail Unit')
                            // KUNCI PERBAIKAN: Ambil data unit di sini
                            ->state(function (Barang $record) {
                                return Barang::where('name', $record->name)
                                    ->where('category_id', $record->category_id)
                                    ->where('room_id', $record->room_id)
                                    ->get();
                            })
                            ->schema([
                                ImageEntry::make('foto')
                                    ->hiddenLabel()
                                    ->circular(),
                                TextEntry::make('kode_barang')
                                    ->label('Kode'),
                                    
                                TextEntry::make('kondisi')
                                    ->label('Kondisi')
                                    ->badge(),
                                TextEntry::make('catatan')
                                    ->label('Catatan')
                                    ->placeholder('-'),
                            ])
                            ->columns(4)
                            
                    ])
                     ])
            ->recordActions([
                EditAction::make(),
                
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
            
    }
}
