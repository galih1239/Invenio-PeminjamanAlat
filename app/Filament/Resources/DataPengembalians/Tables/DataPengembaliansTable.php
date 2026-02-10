<?php

namespace App\Filament\Resources\DataPengembalians\Tables;

use App\Enums\StatusPeminjaman;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifikasiPengembalian;
use Carbon\Carbon;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class DataPengembaliansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada peminjaman barang')
            ->emptyStateDescription('Silahkan ajukan peminjaman barang yang tersedia')

            ->columns([
                ImageColumn::make('barang.foto')
                    ->label('Foto')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->extraImgAttributes([
                        'alt' => 'Foto Barang',
                        'loading' => 'lazy',
                    ]),

                TextColumn::make('barang.kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode barang disalin!')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Str::limit($state, 20))
                    ->tooltip(fn($state) => $state)
                    ->weight('bold'),

                

                TextColumn::make('barang.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => Str::limit($state, 20))
                    ->tooltip(fn($state) => $state)
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(StatusPeminjaman $state) => $state->color())
                    ->formatStateUsing(fn(StatusPeminjaman $state) => $state->label())
                    ->searchable(),
            ])

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->color('warning')
                    ->icon(Heroicon::DocumentCheck)
                    ->visible(
                        fn($record) =>
                        $record->status === StatusPeminjaman::MENUNGGU_VERIFIKASI
                    )
                    ->button()
                    ->modalHeading('Verifikasi Pengembalian')
                    ->modalDescription('Periksa data peminjaman sebelum memverifikasi.')
                    // Menggunakan fillForm agar data dari relasi terisi dengan benar ke dalam form
                    ->fillForm(fn($record) => [
                        'path_bukti_pembayaran' => $record->verifikasiPengembalian?->path_bukti_pembayaran,
                        'catatan' => $record->verifikasiPengembalian?->catatan,
                        'terverifikasi' => true,
                    ])
                    ->form([
                     
                        

Placeholder::make('bukti_preview')
    ->label('Bukti Pembayaran')
    ->content(function ($record) {
        if (!$record?->verifikasiPengembalian?->path_bukti_pembayaran) {
            return '-';
        }

       $url = Storage::url(
    $record->verifikasiPengembalian->path_bukti_pembayaran
);
        return new HtmlString("
            <div class='space-y-2'>
                <img src='{$url}' class='max-h-64 rounded-lg border'/>
                <div>
                    <a href='{$url}' target='_blank' class='text-primary-600 underline'>
                        Lihat
                    </a>
                    &nbsp;|&nbsp;
                    <a href='{$url}' download class='text-primary-600 underline'>
                        Download
                    </a>
                </div>
            </div>
        ");
    }),


// Di-disable agar tidak diubah oleh petugas

                        Textarea::make('catatan')
                            ->label('Catatan Petugas')
                            ->placeholder('Tambahkan catatan verifikasi...'),

                        Toggle::make('terverifikasi')
                            ->label('Terverifikasi')
                            ->default(true)
                            ->required(),
                    ])

                    ->action(function (array $data, $record) {
                        DB::transaction(function () use ($data, $record) {
                            $verifikasi = VerifikasiPengembalian::where('peminjaman_id', $record->id)
                                ->lockForUpdate()
                                ->firstOrFail();

                            // update verifikasi
                            $verifikasi->update([
                                'terverifikasi' => true,
                                'catatan' => $data['catatan'] ?? null,
                                'updated_by' => Auth::id(),
                            ]);

                            // update peminjaman
                            $record->update([
                                'status' => StatusPeminjaman::DIKEMBALIKAN,
                                'updated_by' => Auth::id(),
                            ]);
                        });
                    })
                    ->successNotificationTitle('Pengembalian berhasil diverifikasi')
                    ->failureNotificationTitle('Gagal memverifikasi pengembalian')
                    ->requiresConfirmation()
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}