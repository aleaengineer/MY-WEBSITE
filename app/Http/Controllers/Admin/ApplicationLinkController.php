<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationLinkController extends Controller
{
    public function index(): View
    {
        return view('admin.application-links.index', [
            'applicationLinks' => ApplicationLink::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.application-links.create', [
            'applicationLink' => new ApplicationLink(['is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ApplicationLink::create($this->validatedData($request));

        return redirect()
            ->route('admin.application-links.index')
            ->with('success', 'Tautan aplikasi berhasil ditambahkan.');
    }

    public function edit(ApplicationLink $applicationLink): View
    {
        return view('admin.application-links.edit', [
            'applicationLink' => $applicationLink,
        ]);
    }

    public function update(Request $request, ApplicationLink $applicationLink): RedirectResponse
    {
        $applicationLink->update($this->validatedData($request));

        return redirect()
            ->route('admin.application-links.index')
            ->with('success', 'Tautan aplikasi berhasil diperbarui.');
    }

    public function destroy(ApplicationLink $applicationLink): RedirectResponse
    {
        $applicationLink->delete();

        return back()->with('success', 'Tautan aplikasi berhasil dihapus.');
    }

    public function toggleActive(ApplicationLink $applicationLink): RedirectResponse
    {
        $applicationLink->update(['is_active' => ! $applicationLink->is_active]);

        return back()->with(
            'success',
            $applicationLink->is_active
                ? 'Tautan aplikasi diaktifkan.'
                : 'Tautan aplikasi dinonaktifkan.'
        );
    }

    /**
     * @return array{name: string, url: string, sort_order: int, is_active: bool}
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url:http,https', 'max:2048'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama tautan wajib diisi.',
            'url.required' => 'URL tautan wajib diisi.',
            'url.url' => 'URL harus menggunakan http atau https yang valid.',
            'sort_order.required' => 'Urutan wajib diisi.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan tidak boleh kurang dari 0.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
