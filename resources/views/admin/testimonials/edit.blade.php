<x-admin-layout title="Edit Testimoni">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-white">Edit Testimoni</h2>
        <p class="mt-1 text-sm text-slate-400">{{ $testimonial->name }}</p>
    </div>
    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 sm:p-8">
        @csrf
        @method('PUT')
        @include('admin.testimonials._form', ['submitLabel' => 'Perbarui Testimoni'])
    </form>
</x-admin-layout>
