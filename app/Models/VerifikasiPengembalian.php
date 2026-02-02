<?php

namespace App\Models;

use App\Enums\MethodePembayaran;
use Illuminate\Database\Eloquent\Model;

class VerifikasiPengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'terverifikasi',
        'metode_pembbayaran',
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
        'metode_pembayaran' => MethodePembayaran::class
    ];
}