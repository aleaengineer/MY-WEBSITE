<x-admin-layout title="Profil Saya">
    <div class="mb-8">
        <h2 class="text-xl font-bold tracking-tight text-white">Profil Saya</h2>
        <p class="mt-1 text-sm text-slate-400">Kelola info akun, deskripsi halaman beranda, dan password admin.</p>
    </div>

    <div class="grid max-w-4xl gap-6">
        {{-- Info profil --}}
        <form method="POST" action="{{ route('admin.profile.update') }}" class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <h3 class="font-semibold text-white">Info Akun</h3>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Nama <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                           class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
                    @error('name') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">Email <span class="text-rose-400">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255"
                           class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
                    @error('email') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-5">
                <label for="hero_description" class="mb-2 block text-sm font-medium text-slate-300">Deskripsi Halaman Beranda <span class="text-rose-400">*</span></label>
                <textarea id="hero_description" name="hero_description" rows="3" required maxlength="500"
                          placeholder="Tuliskan deskripsi singkat yang tampil di bagian utama halaman beranda."
                          class="w-full resize-y rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm leading-relaxed text-white placeholder-slate-600 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">{{ old('hero_description', $portfolioProfile->hero_description) }}</textarea>
                @error('hero_description') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                <p class="mt-2 text-xs text-slate-500">Maksimal 500 karakter. Teks diperbarui di hero halaman beranda setelah disimpan.</p>
            </div>

            <button type="submit"
                    class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-90">
                Simpan Profil
            </button>
        </form>

        {{-- Ganti password --}}
        <form method="POST" action="{{ route('admin.profile.password') }}" class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <h3 class="font-semibold text-white">Ganti Password</h3>
            <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter. Wajib memasukkan password lama.</p>

            <div class="mt-5 grid gap-5">
                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-slate-300">Password Lama <span class="text-rose-400">*</span></label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                           class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
                    @error('current_password') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-300">Password Baru <span class="text-rose-400">*</span></label>
                        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
                        @error('password') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">Konfirmasi Password Baru <span class="text-rose-400">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl border border-amber-400/40 bg-amber-400/10 px-6 py-3 text-sm font-semibold text-amber-300 transition hover:border-amber-300 hover:bg-amber-400/20">
                Ganti Password
            </button>
        </form>
    </div>
</x-admin-layout>
