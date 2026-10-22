<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::query()->orderBy('sort_order')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create', [
            'testimonial' => new Testimonial(['is_published' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $testimonial = Testimonial::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')
            ->with('success', "Testimoni dari {$testimonial->name} berhasil ditambahkan.");
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')
            ->with('success', "Testimoni dari {$testimonial->name} berhasil diperbarui.");
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $name = $testimonial->name;
        $testimonial->delete();

        return back()->with('success', "Testimoni dari {$name} berhasil dihapus.");
    }

    public function togglePublished(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_published' => ! $testimonial->is_published]);

        return back()->with(
            'success',
            $testimonial->is_published
                ? "Testimoni dari {$testimonial->name} ditampilkan."
                : "Testimoni dari {$testimonial->name} disembunyikan."
        );
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:3000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
