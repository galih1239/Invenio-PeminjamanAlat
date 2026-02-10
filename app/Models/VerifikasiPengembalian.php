<?php

namespace App\Models;

use App\Enums\MethodePembayaran;
use App\Enums\OpsiBank;
use App\Enums\OpsiEwallet;
use Illuminate\Database\Eloquent\Model;

class VerifikasiPengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'terverifikasi',
        'metode_pembayaran',
        'path_bukti_pembayaran',
        'nama_bank',
        'nama_ewallet',
        'total_bayar',
        'catatan'
    ];
public function peminjaman()
{
    return $this->belongsTo(PeminjamanBarang::class);
}
    protected $casts = [
        'metode_pembayaran' => MethodePembayaran::class,
        'nama_bank' => OpsiBank::class,
        'nama_ewallet' => OpsiEwallet::class
    ];
}