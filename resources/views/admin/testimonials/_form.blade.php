<div class="space-y-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Nama <span class="text-rose-400">*</span></label>
        <input id="name" name="name" value="{{ old('name', $testimonial->name) }}" required maxlength="120" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
        @error('name') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="role" class="mb-2 block text-sm font-medium text-slate-300">Jabatan / Hubungan</label>
            <input id="role" name="role" value="{{ old('role', $testimonial->role) }}" maxlength="120" placeholder="cth: Rekan kerja"
                   class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
            @error('role') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="company" class="mb-2 block text-sm font-medium text-slate-300">Perusahaan</label>
            <input id="company" name="company" value="{{ old('company', $testimonial->company) }}" maxlength="120"
                   class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
            @error('company') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="quote" class="mb-2 block text-sm font-medium text-slate-300">Isi testimoni <span class="text-rose-400">*</span></label>
        <textarea id="quote" name="quote" rows="6" required maxlength="3000" class="w-full resize-y rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm leading-relaxed text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">{{ old('quote', $testimonial->quote) }}</textarea>
        @error('quote') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-300">Urutan tampil</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" required min="0" max="65535" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30">
            @error('sort_order') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-3 self-end rounded-xl border border-slate-800 bg-slate-800/40 p-4 text-sm text-slate-300">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->is_published)) class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-indigo-500 focus:ring-indigo-500/40">
            Tampilkan di halaman publik
        </label>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90">{{ $submitLabel }}</button>
        <a href="{{ route('admin.testimonials.index') }}" class="rounded-xl border border-slate-700 px-6 py-3 text-center text-sm font-medium text-slate-300 hover:text-white">Batal</a>
    </div>
</div>
