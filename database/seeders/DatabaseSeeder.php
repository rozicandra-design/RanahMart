<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama_depan'   => 'Super',
                'nama_belakang' => 'Admin',
                'email'        => 'admin@ranahmart.com',
                'password'     => Hash::make('password'),
                'role'         => 'admin',
            ],
            [
                'nama_depan'   => 'Dinas Koperasi',
                'nama_belakang' => 'Padang',
                'email'        => 'dinas@ranahmart.com',
                'password'     => Hash::make('password'),
                'role'         => 'dinas',
            ],
            [
                'nama_depan'   => 'Rendang',
                'nama_belakang' => 'Uni Siti',
                'email'        => 'unisiti@ranahmart.com',
                'password'     => Hash::make('password'),
                'role'         => 'penjual',
            ],
            [
                'nama_depan'   => 'Kerajinan',
                'nama_belakang' => 'Pak Budi',
                'email'        => 'pakbudi@ranahmart.com',
                'password'     => Hash::make('password'),
                'role'         => 'penjual',
            ],
            [
                'nama_depan'   => 'Batik Minang',
                'nama_belakang' => 'Store',
                'email'        => 'batikminang@ranahmart.com',
                'password'     => Hash::make('password'),
                'role'         => 'penjual',
            ],
            [
                'nama_depan'   => 'Budi',
                'nama_belakang' => 'Santoso',
                'email'        => 'budi@gmail.com',
                'password'     => Hash::make('password'),
                'role'         => 'pembeli',
            ],
            [
                'nama_depan'   => 'Sari',
                'nama_belakang' => 'Dewi',
                'email'        => 'sari@gmail.com',
                'password'     => Hash::make('password'),
                'role'         => 'pembeli',
            ],
            [
                'nama_depan'   => 'Ahmad',
                'nama_belakang' => 'Fauzi',
                'email'        => 'ahmad@gmail.com',
                'password'     => Hash::make('password'),
                'role'         => 'pembeli',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('✅ Seeder selesai! Akun yang dibuat:');
        $this->command->table(
            ['Nama', 'Email', 'Role', 'Password'],
            collect($users)->map(fn($u) => [
                $u['nama_depan'].' '.$u['nama_belakang'],
                $u['email'],
                $u['role'],
                'password'
            ])->toArray()
        );
    }
}