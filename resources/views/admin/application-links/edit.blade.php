<x-admin-layout title="Edit Tautan Aplikasi">
    <div class="mb-8">
        <h2 class="text-xl font-bold tracking-tight text-white">Edit Tautan Aplikasi</h2>
        <p class="mt-1 text-sm text-slate-400">{{ $applicationLink->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.application-links.update', $applicationLink) }}" class="max-w-3xl rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
        @csrf
        @method('PUT')
        @include('admin.application-links._form', ['submitLabel' => 'Perbarui Tautan'])
    </form>
</x-admin-layout>
