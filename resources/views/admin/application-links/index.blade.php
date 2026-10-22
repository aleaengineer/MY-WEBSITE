<x-admin-layout title="Tautan Aplikasi">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-white">Tautan Aplikasi</h2>
            <p class="mt-1 text-sm text-slate-400">Kelola tautan yang muncul pada dropdown Aplikasi di navbar publik.</p>
        </div>
        <a href="{{ route('admin.application-links.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-90">
            Tambah Tautan
        </a>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-800 bg-slate-800/40">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-slate-800 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Nama / URL</th>
                        <th class="px-6 py-4 font-medium">Urutan</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse ($applicationLinks as $applicationLink)
                        <tr class="transition hover:bg-slate-800/30">
                            <td class="px-6 py-4">
                                <p class="font-medium text-white">{{ $applicationLink->name }}</p>
                                <a href="{{ $applicationLink->url }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-1 block max-w-lg truncate text-xs text-slate-500 hover:text-cyan-300">
                                    {{ $applicationLink->url }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $applicationLink->sort_order }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.application-links.toggleActive', $applicationLink) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold transition {{ $applicationLink->is_active ? 'bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/30' : 'bg-slate-500/10 text-slate-400 ring-1 ring-slate-600/40' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $applicationLink->is_active ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                        {{ $applicationLink->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.application-links.edit', $applicationLink) }}"
                                       class="rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-xs font-medium text-slate-300 transition hover:border-indigo-500/40 hover:text-indigo-300">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.application-links.destroy', $applicationLink) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus tautan &quot;{{ addslashes($applicationLink->name) }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-xs font-medium text-slate-400 transition hover:border-rose-500/40 hover:text-rose-400">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center text-sm text-slate-500">
                                Belum ada tautan aplikasi. Tambahkan tautan agar dropdown tampil di navbar publik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-pagination :paginator="$applicationLinks" />
</x-admin-layout>
