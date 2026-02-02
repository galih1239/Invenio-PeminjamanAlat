<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
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
        'tanggal_disetujui' =>'date',
        'tanggal_pinjam'=>'date',
        'tanggal_kembali'=>'date',
        'status'=>StatusPeminjaman::class,
    ];
    public function peminjam()
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
    

}