<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PertumbuhanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('user-12345');
        $namaDepan = [
            'Adi',
            'Budi',
            'Udin',
            'Rizki',
            'Andi',
            'Fajar',
            'Ilham',
            'Agus',
            'Eko',
            'Rian',
            'Putra',
            'Yoga',
            'Bagus',
            'Arif',
            'Agus',
            'Salim',
            'Rohman'
        ];

        $namaBelakang = [
            'Saputra',
            'Pratama',
            'Wijaya',
            'Santoso',
            'Nugroho',
            'Hidayat',
            'Setiawan',
            'Permana',
            'Ramadhan',
            'Firmansyah',
            'Setyawan',
            'Widodo'
        ];
        $monthlyUsers = [
            5,
            8,
            12,
            10,
            15,
            20,
            25,
            18,
            22,
            30,
            28,
            35
        ];
        foreach ($monthlyUsers as $index => $count) {
            $date = Carbon::now()->subMonths(11 - $index);

            for ($i = 1; $i <= $count; $i++) {
                $depan = $namaDepan[array_rand($namaDepan)];
                $belakang = $namaBelakang[array_rand($namaBelakang)];
                $namaLengkap = "$depan $belakang";

                // Email khas Indonesia
                $email = Str::slug($depan . $belakang) .
                    $date->format('ym') .
                    $i .
                    '@gmail.com';

                User::create([
                    'name' => $namaLengkap,
                    'email' => strtolower($email),
                    'role' => 'pengguna',
                    'is_active' => 1,
                    'password' => $password,
                    'email_verified_at' => now(),
                    'created_at' => $date->copy()->addDays(rand(0, 27)),
                    'updated_at' => now(),
                ]);
            }
        
    }
    
    }
}