<?php

namespace App\Filament\Resources\PeminjamanBarangs\Tables;

use App\Enums\StatusPeminjaman;
use App\Enums\MethodePembayaran;
use App\Enums\OpsiBank;
use App\Enums\OpsiEwallet;
use App\Models\User;
use App\Models\VerifikasiPengembalian;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Carbon;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeminjamanBarangsTable
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
                // Batalkan (Belum Di setujui)
                Action::make('batalkan')
                    ->label('Batalkan')
                    ->color('danger')
                    ->icon(Heroicon::XCircle)
                    ->visible(
                        fn($record) =>
                        $record->status === StatusPeminjaman::BELUM_DISETUJUI
                    )
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pengajuan?')
                    ->modalDescription("Anda yakin ingin membatalkan pengajuan?")
                    ->modalIcon(Heroicon::OutlinedXCircle)
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(function ($record) {
                        $record->update([
                            'status' => StatusPeminjaman::DIBATALKAN,
                            'updated_at' => now()
                        ]);
                    }),

                // Kembalikan Barang
                 Action::make('kembalikan')
                 ->label('Kembalikan')
                 ->color('success')
                 ->icon(Heroicon::Pencil)
                 ->visible(
                     fn($record) =>
                     $record->status === StatusPeminjaman::DIPINJAM
                 )
                 ->button()
                 ->requiresConfirmation()
                 ->modalHeading('Kembalikan Barang?')
                 ->modalDescription("Anda yakin ingin mengembalikan barang yang anda pinjam?")
                 ->modalIcon(Heroicon::OutlinedCheckBadge)
                 ->modalSubmitActionLabel('Ya, Kembalikan')
                 ->action(function ($record) {
                     $record->update([
                         'status' => StatusPeminjaman::DIKEMBALIKAN,
                         'updated_at' => now()
                     ]);
                }),

                // Kembalikan Barang (Terlambat

Action::make('kembalikan_terlambat')
    ->label('Kembalikan')
    ->button()
    ->color(Color::Red)
    ->icon(Heroicon::Clock)
    ->visible(fn ($record) => $record->status === StatusPeminjaman::TERLAMBAT)
    ->requiresConfirmation()
    ->modalHeading('Konfirmasi Pengembalian & Pembayaran')

    // 🔹 HANYA UNTUK TAMPILAN
    ->modalDescription(function ($record) {
        $hariTerlambat = Carbon::parse($record->tanggal_kembali)
            ->startOfDay()
           ->diffInDays(now()->startOfDay(), false);

        $denda = $hariTerlambat * 5000;

        return new HtmlString(
            "Anda terlambat <strong>{$hariTerlambat} hari</strong>.<br>
             Total denda: <strong>Rp " . number_format($denda) . "</strong>"
        );
    })

    // 🔥 INI YANG SEBELUMNYA KAMU BELUM PUNYA
    ->action(function ($record) {

        // 1️⃣ hitung ulang (WAJIB, JANGAN PERCAYA UI)
        $hariTerlambat = Carbon::parse($record->tanggal_kembali)
            ->startOfDay()
            ->diffInDays(now()->startOfDay());

        $totalBayar = max(0, $hariTerlambat * 5000);

        // 2️⃣ simpan ke verifikasi_pengembalians
        VerifikasiPengembalian::updateOrCreate(
            ['peminjaman_id' => $record->id],
            [
                'total_bayar'   => $totalBayar,
                'terverifikasi' => false,
            ]
        );

        // 3️⃣ update status peminjaman
        $record->update([
            'status' => StatusPeminjaman::MENUNGGU_VERIFIKASI,
        ]);
    })

                    
                    ->form([

                        FileUpload::make('path_bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->disk('public')
                            ->directory('bukti_pembayaran')
                            ->required()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->imagePreviewHeight('200'),

                        TextInput::make('keterangan')
                            ->label('Catatan (opsional)')
                            ->placeholder('Contoh: bayar via QRIS BCA'),
                    ])
                    ->modalSubmitActionLabel('Kembalikan & Bayar')
                    ->action(function ($record, array $data) {
                        DB::beginTransaction();

                        try {
                            $hariTerlambat = Carbon::parse($record->tanggal_kembali)
                                ->startOfDay()
                                ->diffInDays(now()->startOfDay());

                            $totalBayar = $hariTerlambat * 5000;

                            $record->update([
                                'status' => StatusPeminjaman::MENUNGGU_VERIFIKASI,
                                'updated_by' => $record->peminjaman_id,
                            ]);

                            VerifikasiPengembalian::create([
                                'peminjaman_id'          => $record->id,
                                'terverifikasi'          => false,
                                'path_bukti_pembayaran' => $data['path_bukti_pembayaran'],
                                'catatan'               => $data['keterangan'] ?? null,
                            ]);

                            DB::commit();

                            Notification::make()
                                ->title('Pengembalian berhasil dikirim')
                                ->body('Menunggu verifikasi petugas.')
                                ->success()
                                ->send();

                            Notification::make()
                                ->title('Pengembalian berhasil dikirim')
                                ->body('Menunggu verifikasi petugas.')
                                ->success()
                                ->icon(Heroicon::Clock)
                                ->sendToDatabase(Auth::user());
                        } catch (\Throwable $e) {
                            DB::rollBack();

                            report($e);

                            Notification::make()
                                ->title('Gagal mengirim pengembalian')
                                ->body('Terjadi kesalahan, silakan coba lagi.')
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}