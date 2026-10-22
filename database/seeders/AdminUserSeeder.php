<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Buat atau perbarui akun admin dengan kredensial yang dikonfigurasi.
     */
    public function run(): void
    {
        $email = (string) config('admin.email', 'admin@portfolio.test');
        $password = (string) config('admin.password', '');
        $name = (string) config('admin.name', 'Farhan Maulana Syidiq');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException("ADMIN_EMAIL tidak valid: {$email}");
        }

        if ($password === '') {
            throw new \RuntimeException(
                'ADMIN_PASSWORD wajib diisi sebelum menjalankan seeder. '.
                'Set nilainya di .env lalu jalankan ulang seeder.'
            );
        }

        if (strlen($password) < 8) {
            throw new \RuntimeException('ADMIN_PASSWORD minimal 8 karakter.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ],
        );

        if (app()->environment('local')) {
            $this->command?->info("Akun admin dibuat untuk {$email}.");
        }
    }
}
