<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_seed_requires_an_explicit_password(): void
    {
        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => '',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ADMIN_PASSWORD wajib diisi');

        (new AdminUserSeeder())->run();
    }

    public function test_admin_seed_uses_the_configured_password(): void
    {
        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => 'unique-test-password',
            'admin.name' => 'Test Admin',
        ]);

        (new AdminUserSeeder())->run();

        $user = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->assertTrue(Hash::check('unique-test-password', $user->password));
    }
}
