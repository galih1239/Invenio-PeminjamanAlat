<?php

namespace App\Enums;

enum HakAkses :string
{
    case ADMIN = 'admin';
    case PETUGAS = 'petugas';
    case PENGGUNA = 'pengguna';

public function label() {
return match ($this){
    self::ADMIN => 'admin',
    self::PETUGAS => 'petugas',
    self::PENGGUNA => 'pengguna',
    
};

}

}

