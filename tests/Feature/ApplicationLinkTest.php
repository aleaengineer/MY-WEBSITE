<?php

namespace Tests\Feature;

use App\Models\ApplicationLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_mengelola_tautan_aplikasi_dan_menampilkannya_di_navbar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.application-links.index'))
            ->assertOk()
            ->assertSee('Config Farhanale')
            ->assertSee('Tautan Aplikasi');

        $this->actingAs($user)
            ->post(route('admin.application-links.store'), [
                'name' => 'Monitoring',
                'url' => 'https://monitoring.example.com',
                'sort_order' => 2,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.application-links.index'));

        $link = ApplicationLink::where('name', 'Monitoring')->firstOrFail();

        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertSee('Monitoring')
            ->assertSee('href="https://monitoring.example.com"', false);

        $this->put(route('admin.application-links.update', $link), [
            'name' => 'Monitoring Jaringan',
            'url' => 'https://status.example.com',
            'sort_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.application-links.index'));

        $this->assertDatabaseHas('application_links', [
            'id' => $link->id,
            'name' => 'Monitoring Jaringan',
            'url' => 'https://status.example.com',
            'sort_order' => 1,
        ]);

        $this->patch(route('admin.application-links.toggleActive', $link))
            ->assertRedirect();

        $this->assertDatabaseHas('application_links', [
            'id' => $link->id,
            'is_active' => false,
        ]);

        $this->get(route('portfolio.index'))
            ->assertDontSee('href="https://status.example.com"', false);

        $this->delete(route('admin.application-links.destroy', $link))
            ->assertRedirect();

        $this->assertDatabaseMissing('application_links', ['id' => $link->id]);
    }

    public function test_guest_dialihkan_ke_login_dari_pengelolaan_tautan_aplikasi(): void
    {
        $this->get(route('admin.application-links.index'))
            ->assertRedirect(route('login'));
    }

    public function test_tautan_aplikasi_hanya_menerima_url_http_dan_https(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('admin.application-links.create'))
            ->post(route('admin.application-links.store'), [
                'name' => 'Tautan Tidak Valid',
                'url' => 'javascript:alert(1)',
                'sort_order' => 0,
            ])
            ->assertRedirect(route('admin.application-links.create'))
            ->assertSessionHasErrors('url');

        $this->assertDatabaseMissing('application_links', [
            'name' => 'Tautan Tidak Valid',
        ]);
    }
}
