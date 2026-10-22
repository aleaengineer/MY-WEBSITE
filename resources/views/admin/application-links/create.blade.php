<x-admin-layout title="Tambah Tautan Aplikasi">
    <div class="mb-8">
        <h2 class="text-xl font-bold tracking-tight text-white">Tambah Tautan Aplikasi</h2>
        <p class="mt-1 text-sm text-slate-400">Tautan aktif akan muncul di dropdown Aplikasi pada navbar.</p>
    </div>

    <form method="POST" action="{{ route('admin.application-links.store') }}" class="max-w-3xl rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
        @csrf
        @include('admin.application-links._form', ['submitLabel' => 'Simpan Tautan'])
    </form>
</x-admin-layout>
