<x-admin-layout title="Kelola Testimoni">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-white">Testimoni</h2>
            <p class="mt-1 text-sm text-slate-400">Kelola rekomendasi yang ditampilkan di halaman beranda.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">Tambah Testimoni</a>
    </div>

    <div class="mt-8 space-y-4">
        @forelse ($testimonials as $testimonial)
            <article class="rounded-2xl border border-slate-800 bg-slate-800/40 p-5 sm:p-6">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-semibold text-white">{{ $testimonial->name }}</h3>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $testimonial->is_published ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-700 text-slate-400' }}">
                                {{ $testimonial->is_published ? 'Tayang' : 'Draft' }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-400">{{ collect([$testimonial->role, $testimonial->company])->filter()->join(' · ') ?: 'Tanpa keterangan jabatan' }}</p>
                        <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-slate-300">{{ $testimonial->quote }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <form method="POST" action="{{ route('admin.testimonials.togglePublished', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <button class="rounded-lg border border-slate-700 px-3 py-2 text-xs font-medium text-slate-300 transition hover:border-emerald-500/40 hover:text-emerald-300">
                                {{ $testimonial->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="rounded-lg border border-slate-700 px-3 py-2 text-xs font-medium text-slate-300 transition hover:border-indigo-500/40 hover:text-indigo-300">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-lg border border-slate-700 px-3 py-2 text-xs font-medium text-slate-400 transition hover:border-rose-500/40 hover:text-rose-300">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-700 p-10 text-center text-sm text-slate-400">Belum ada testimoni.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $testimonials->links() }}</div>
</x-admin-layout>
