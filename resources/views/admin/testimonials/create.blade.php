<x-admin-layout title="Tambah Testimoni">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-white">Tambah Testimoni</h2>
        <p class="mt-1 text-sm text-slate-400">Pastikan Anda memiliki izin untuk mempublikasikan kutipan ini.</p>
    </div>
    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
        @csrf
        @include('admin.testimonials._form', ['submitLabel' => 'Simpan Testimoni'])
    </form>
</x-admin-layout>
