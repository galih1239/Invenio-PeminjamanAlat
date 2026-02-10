<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeminjamanBarang extends Model
{
    use SoftDeletes;

    //mendefinisikan tabel yang bisa diisi
    protected $fillable = [
        'peminjaman_id',
        'petugas_id',
        'barang_id',
        'keperluan',
        'tanggal_disetujui',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',

    ];
    //untuk memastikan data yang kita input dari aplikasi sesuai dengan tipe data di database
    protected $casts = [
        'tanggal_disetujui' => 'date',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'status' => StatusPeminjaman::class,
    ];
    public function hitungTotalBayar(): int
    {
        $total = 0;

        // contoh: denda keterlambatan
        if ($this->verifikasiPengembalian?->terlambat_hari > 0) {
            $total += $this->verifikasiPengembalian->terlambat_hari * 1000;
        }

        // contoh: denda kerusakan
        if ($this->verifikasiPengembalian?->kondisi === 'rusak') {
            $total += $this->barang->harga_rusak ?? 0;
        }

        return $total;
    }

    public function peminjaman()
    {
        return $this->belongsTo(User::class, 'peminjaman_id');
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function verifikasiPengembalian()
    {
        return $this->hasOne(VerifikasiPengembalian::class, 'peminjaman_id');
    }
    public function hitungDenda(): int
    {
        if (! $this->tanggal_kembali || ! $this->tanggal_harus_kembali) {
            return 0;
        }

        $hariTerlambat = max(
            0,
            Carbon::parse($this->tanggal_harus_kembali)
                ->diffInDays(Carbon::parse($this->tanggal_kembali))
        );

        return $hariTerlambat * ($this->denda_per_hari ?? 0);
    }

}
