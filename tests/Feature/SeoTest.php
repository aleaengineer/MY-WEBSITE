<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_menghasilkan_xml_dengan_charset_utf8_dan_memiliki_root_urlset(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());

        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertSame('urlset', $xml->getName());
    }

    public function test_sitemap_memakai_deklarasi_xml_dan_tidak_memuat_halaman_pencarian(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $response->assertDontSee(route('search.index'));
    }

    public function test_robots_txt_mengizinkan_publik_dan_menutup_area_admin(): void
    {
        $response = $this->get(route('robots'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Allow: /');
        $response->assertSee('Disallow: /admin');
        $response->assertSee('Disallow: /login');
        $response->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_halaman_pencarian_memakai_noindex(): void
    {
        $this->get(route('search.index'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_halaman_login_dan_admin_memakai_noindex(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_halaman_utama_memiliki_structured_data_person_dan_website(): void
    {
        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertSee('"@type":"Person"', false)
            ->assertSee('"@type":"WebSite"', false);
    }

    public function test_detail_proyek_memiliki_structured_data_breadcrumb(): void
    {
        $project = Project::create([
            'title' => 'Contoh Proyek SEO',
            'description' => 'Deskripsi proyek untuk pengujian.',
            'tech_stack' => [],
        ]);

        $this->get(route('portfolio.projects.show', $project))
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_artikel_memiliki_structured_data_blogposting(): void
    {
        $post = Post::create([
            'title' => 'Artikel SEO',
            'content' => 'Isi artikel untuk pengujian.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('"@type":"BlogPosting"', false)
            ->assertSee('property="article:published_time"', false);
    }

    public function test_canonical_mempertahankan_nomor_halaman_paginasi(): void
    {
        $this->get(route('blog.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('blog.index').'?page=2">', false);
    }

    public function test_canonical_mempertahankan_filter_kategori(): void
    {
        $this->get(route('blog.index', ['kategori' => 'Web Dev']))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('blog.index', ['kategori' => 'Web Dev']).'">', false);
    }

    public function test_canonical_mengabaikan_parameter_tracking(): void
    {
        $this->get(route('blog.index', ['utm_source' => 'newsletter']))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('blog.index').'">', false);
    }

    public function test_meta_description_artikel_tidak_mengandung_baris_baru(): void
    {
        $post = Post::create([
            'title' => 'Artikel Multi Baris',
            'content' => "# Judul Isi\n\nParagraf pertama.\nParagraf kedua.",
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('blog.show', $post));
        $response->assertOk();

        preg_match('/<meta name="description" content="([^"]*)">/', $response->getContent(), $matches);

        $this->assertNotEmpty($matches[1] ?? null);
        $this->assertStringNotContainsString("\n", $matches[1]);
    }
}
