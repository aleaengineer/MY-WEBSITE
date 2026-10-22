<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\ContactSetting;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PortfolioProfile;
use App\Models\Post;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Halaman utama: tampilkan proyek unggulan.
     */
    public function index(): View
    {
        $projects = Project::query()
            ->featured()
            ->orderByDesc('id')
            ->get();

        $earliestStart = Experience::query()->min('start_date');

        return view('portfolio.index', [
            'projects' => $projects,
            'portfolioProfile' => PortfolioProfile::current(),
            'contactSetting' => ContactSetting::current(),
            'testimonials' => Testimonial::query()
                ->published()
                ->ordered()
                ->get(),
            'stats' => [
                'years' => $earliestStart
                    ? max(1, now()->diffInYears(Carbon::parse($earliestStart)))
                    : 0,
                'projects' => $projects->count(),
                'posts' => Post::published()->count(),
                'certifications' => Certification::count(),
            ],
        ]);
    }

    /**
     * Halaman Keahlian (standalone, seperti Blog).
     */
    public function skills(): View
    {
        return view('portfolio.skills');
    }

    /**
     * Halaman Proyek: tampilkan semua proyek (unggulan dulu).
     */
    public function projects(Request $request): View
    {
        $category = $request->query('kategori');
        $category = is_string($category) ? $category : null;
        $projects = Project::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderByDesc('is_featured')
            ->orderByDesc('id')
            ->get();

        return view('portfolio.projects', [
            'projects' => $projects,
            'categories' => Project::query()
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'selectedCategory' => $category,
        ]);
    }

    /**
     * Detail proyek berdasarkan slug.
     */
    public function showProject(Project $project): View
    {
        $related = Project::query()
            ->whereKeyNot($project->id)
            ->when($project->category, fn ($query) => $query->where('category', $project->category))
            ->orderByDesc('is_featured')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('portfolio.projects-show', [
            'project' => $project,
            'related' => $related,
        ]);
    }

    /**
     * Halaman Pengalaman & Sertifikasi (standalone, seperti Blog).
     */
    public function experience(): View
    {
        return view('portfolio.experience', [
            'experiences' => Experience::query()
                ->orderBy('sort_order')
                ->orderByDesc('start_date')
                ->get(),
            'certifications' => Certification::query()
                ->orderBy('sort_order')
                ->orderByDesc('issue_date')
                ->get(),
            'educations' => Education::query()
                ->orderBy('sort_order')
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }

    /**
     * Detail pengalaman kerja.
     */
    public function showExperience(Experience $experience): View
    {
        $others = Experience::query()
            ->whereKeyNot($experience->id)
            ->orderBy('sort_order')
            ->orderByDesc('start_date')
            ->take(3)
            ->get();

        return view('portfolio.experience-show', [
            'experience' => $experience,
            'others' => $others,
        ]);
    }

    /**
     * Halaman CV siap cetak (data live dari database).
     */
    public function cv(): View
    {
        return view('portfolio.cv', [
            'contactSetting' => ContactSetting::current(),
            'experiences' => Experience::query()
                ->orderBy('sort_order')
                ->orderByDesc('start_date')
                ->get(),
            'certifications' => Certification::query()
                ->orderBy('sort_order')
                ->orderByDesc('issue_date')
                ->get(),
            'educations' => Education::query()
                ->orderBy('sort_order')
                ->orderByDesc('start_date')
                ->get(),
            'projects' => Project::query()
                ->featured()
                ->orderByDesc('id')
                ->take(6)
                ->get(),
        ]);
    }

    /**
     * Halaman Kontak (standalone, seperti Blog).
     */
    public function contact(): View
    {
        return view('portfolio.contact', [
            'contactSetting' => ContactSetting::current(),
        ]);
    }

    /**
     * Simpan pesan dari form kontak.
     */
    public function storeContact(Request $request): RedirectResponse
    {
        $request->validate([
            'website' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->filled('website')) {
            return back()->with(
                'success',
                'Terima kasih! Pesan Anda sudah terkirim dan akan saya balas secepatnya.'
            );
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:filter', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'subject.required' => 'Subjek wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        Message::create($validated);

        return back()->with(
            'success',
            'Terima kasih! Pesan Anda sudah terkirim dan akan saya balas secepatnya.'
        );
    }
}
