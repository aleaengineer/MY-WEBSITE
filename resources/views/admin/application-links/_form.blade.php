<div class="space-y-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Nama Tautan <span class="text-rose-400">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $applicationLink->name) }}" required maxlength="100" placeholder="cth: Config Farhanale"
               class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-600 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
        @error('name') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="url" class="mb-2 block text-sm font-medium text-slate-300">URL <span class="text-rose-400">*</span></label>
        <input type="url" id="url" name="url" value="{{ old('url', $applicationLink->url) }}" required maxlength="2048" placeholder="https://contoh.domain"
               class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-600 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
        @error('url') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        <p class="mt-2 text-xs text-slate-500">Gunakan URL lengkap dengan protokol http:// atau https://. Tautan publik dibuka di tab baru.</p>
    </div>

    <div>
        <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-300">Urutan <span class="text-rose-400">*</span></label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $applicationLink->sort_order ?? 0) }}" required min="0" step="1"
               class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
        @error('sort_order') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        <p class="mt-2 text-xs text-slate-500">Angka lebih kecil tampil lebih dahulu; nama digunakan untuk mengurutkan nilai yang sama.</p>
    </div>

    <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-slate-800 bg-slate-800/40 p-4">
        <span>
            <span class="block text-sm font-medium text-slate-300">Aktif</span>
            <span class="mt-1 block text-xs text-slate-500">Tampilkan tautan ini di dropdown navbar publik.</span>
        </span>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $applicationLink->is_active) ? 'checked' : '' }}
               class="h-5 w-5 rounded border-slate-700 bg-slate-950 text-indigo-500 focus:ring-indigo-500/40">
    </label>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-90">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.application-links.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-800/50 px-6 py-3 text-sm font-medium text-slate-300 transition hover:border-slate-500 hover:text-white">
            Batal
        </a>
    </div>
</div>
