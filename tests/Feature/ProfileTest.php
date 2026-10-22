<?php

namespace Tests\Feature;

use App\Models\PortfolioProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_mengubah_deskripsi_hero_dari_halaman_profil(): void
    {
        $user = User::factory()->create();
        $description = 'Saya membangun otomasi jaringan dan aplikasi web untuk ISP.';

        $this->actingAs($user)
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('Deskripsi Halaman Beranda');

        $this->actingAs($user)
            ->put(route('admin.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'hero_description' => $description,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame($description, PortfolioProfile::current()->hero_description);

        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertSee($description);
    }

    public function test_deskripsi_hero_wajib_diisi_dan_memiliki_batas_500_karakter(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'hero_description' => str_repeat('x', 501),
            ])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHasErrors('hero_description');

        $this->assertSame(
            PortfolioProfile::DEFAULT_HERO_DESCRIPTION,
            PortfolioProfile::current()->hero_description,
        );
    }
}
