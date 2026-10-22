<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Form profil & ganti password.
     */
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
            'portfolioProfile' => PortfolioProfile::current(),
        ]);
    }

    /**
     * Perbarui nama & email.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:filter', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'hero_description' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email sudah dipakai akun lain.',
            'hero_description.required' => 'Deskripsi halaman beranda wajib diisi.',
            'hero_description.max' => 'Deskripsi halaman beranda maksimal 500 karakter.',
        ]);

        DB::transaction(function () use ($user, $validated): void {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            PortfolioProfile::current()->update([
                'hero_description' => $validated['hero_description'],
            ]);
        });

        return back()->with('success', 'Profil dan deskripsi halaman beranda berhasil diperbarui.');
    }

    /**
     * Ganti password dengan verifikasi password lama.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak cocok.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = auth()->user();
        $user->update(['password' => Hash::make($request->string('password')->toString())]);

        return back()->with('success', 'Password berhasil diganti.');
    }
}
