<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_can_be_filtered_by_category_and_case_study_metadata_is_rendered(): void
    {
        $networkProject = Project::create([
            'title' => 'FTTH Network Map',
            'description' => 'Peta infrastruktur jaringan.',
            'category' => 'Networking',
            'role' => 'NOC Engineer',
            'problem' => 'Data coverage tersebar.',
            'solution' => 'Membuat peta terpusat.',
            'results' => 'Tim lebih mudah melihat cakupan.',
            'seo_title' => 'FTTH Mapping Case Study',
            'seo_description' => 'Case study pemetaan FTTH.',
            'tech_stack' => ['Laravel'],
            'is_featured' => true,
        ]);

        Project::create([
            'title' => 'Internal Web App',
            'description' => 'Aplikasi internal.',
            'category' => 'Web Development',
            'tech_stack' => ['PHP'],
        ]);

        $this->get(route('portfolio.projects', ['kategori' => 'Networking']))
            ->assertOk()
            ->assertSee($networkProject->title)
            ->assertDontSee('Internal Web App');

        $this->get(route('portfolio.projects.show', $networkProject))
            ->assertOk()
            ->assertSee('FTTH Mapping Case Study')
            ->assertSee('Case study pemetaan FTTH.')
            ->assertSee('NOC Engineer')
            ->assertSee('Tantangan')
            ->assertSee('Membuat peta terpusat.')
            ->assertSee('Hasil & Dampak');
    }

    public function test_admin_can_save_project_case_study_and_seo_fields(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('admin.projects.store'), [
                'title' => 'Monitoring ISP',
                'description' => 'Dashboard pemantauan layanan.',
                'category' => 'Networking',
                'role' => 'NOC Engineer',
                'problem' => 'Monitoring dilakukan manual.',
                'solution' => 'Membuat dashboard terpusat.',
                'results' => 'Status layanan terlihat lebih cepat.',
                'seo_title' => 'Monitoring ISP Case Study',
                'seo_description' => 'Automasi monitoring untuk ISP.',
                'tech_stack' => 'Laravel, MySQL',
                'is_featured' => '1',
            ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', [
            'title' => 'Monitoring ISP',
            'category' => 'Networking',
            'problem' => 'Monitoring dilakukan manual.',
            'solution' => 'Membuat dashboard terpusat.',
            'results' => 'Status layanan terlihat lebih cepat.',
            'seo_title' => 'Monitoring ISP Case Study',
        ]);
    }

    public function test_analytics_accepts_a_project_with_a_maximum_length_slug(): void
    {
        $project = Project::create([
            'title' => 'Long slug project',
            'slug' => str_repeat('x', 255),
            'description' => 'Project with a valid long slug.',
            'tech_stack' => [],
        ]);

        $this->get(route('portfolio.projects.show', $project))->assertOk();
        $this->assertDatabaseHas('page_views', [
            'visit_date' => today()->toDateString(),
            'path' => '/proyek/'.str_repeat('x', 255),
            'views' => 1,
        ]);
    }

    public function test_published_testimonials_are_manageable_and_render_on_the_homepage(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('admin.testimonials.store'), [
            'name' => 'Ayu Pratiwi',
            'role' => 'Rekan kerja',
            'company' => 'Contoh ISP',
            'quote' => 'Farhan selalu responsif dan teliti.',
            'sort_order' => 1,
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Ayu Pratiwi',
            'is_published' => true,
        ]);

        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertSee('Apa Kata Mereka')
            ->assertSee('Farhan selalu responsif dan teliti.');
    }

    public function test_admin_dashboard_shows_aggregated_analytics_and_tracks_public_pages_only(): void
    {
        $this->get(route('portfolio.index'))->assertOk();

        $this->assertDatabaseHas('page_views', [
            'visit_date' => today()->toDateString(),
            'path' => '/',
            'views' => 1,
        ]);

        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertOk();
        $this->assertDatabaseCount('page_views', 1);

        $this->get(route('admin.dashboard'))
            ->assertSee('Analitik Situs')
            ->assertSee('Tampilan hari ini');
    }

    public function test_contact_honeypot_prevents_spam_from_being_saved(): void
    {
        $this->post(route('portfolio.contact'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => 'Pesan spam',
            'website' => 'spam.example',
        ])->assertRedirect();

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_unpublished_testimonials_are_not_visible_publicly(): void
    {
        Testimonial::create([
            'name' => 'Draft',
            'quote' => 'Konten belum disetujui.',
            'is_published' => false,
        ]);

        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertDontSee('Konten belum disetujui.');
    }
}
