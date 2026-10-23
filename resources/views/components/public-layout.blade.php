{{--
    Layout bersama halaman publik (landing page + blog).
    Props: $title, $description, $image (URL absolut opsional), $type (website/article).
    Konten halaman diisi lewat $slot.
--}}
@props([
    'title' => 'Farhan Maulana Syidiq — NOC Engineer & Web Developer',
    'description' => 'Portofolio Farhan Maulana Syidiq, Netwok Engineer & Network Administrator. Spesialis MikroTik RouterOS, GPON/EPON, Proxmox VE, dan Laravel.',
    'image' => null,
    'type' => 'website',
    'robots' => 'index, follow',
])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="Farhan Maulana Syidiq">
    <meta name="robots" content="{{ $robots }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @php
        $socialImage = $image ? url($image) : null;
        $canonical = url()->current();
        $query = collect(request()->query())
            ->only(['kategori', 'tag', 'q'])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();
        $page = request()->integer('page');

        if ($page > 1) {
            $query['page'] = $page;
        }

        if ($query !== []) {
            ksort($query);
            $canonical .= '?'.http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }
    @endphp
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if ($socialImage)
        <meta property="og:image" content="{{ $socialImage }}">
        <meta property="og:image:alt" content="{{ $title }}">
    @endif
    <meta name="twitter:card" content="{{ $socialImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    @if ($socialImage)
        <meta name="twitter:image" content="{{ $socialImage }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} — Blog" href="{{ route('blog.feed') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="public-site overflow-x-clip bg-slate-900 font-sans text-slate-100 antialiased selection:bg-indigo-500/40">

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="sticky top-0 z-50 border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-md" x-data="{ open: false }">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('portfolio.index') }}" class="flex items-center gap-2.5 text-lg font-bold tracking-tight">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Farhan Maulana Syidiq" width="35" height="32" style="height:32px;width:auto;" class="mix-blend-screen">
                <span>Farhan<span class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">MS</span></span>
            </a>

            {{-- Menu desktop --}}
            <div class="hidden items-center gap-6 lg:flex xl:gap-8">
                <a href="{{ route('portfolio.index') }}" class="text-sm font-medium transition {{ request()->routeIs('portfolio.index') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Beranda</a>
                <a href="{{ route('portfolio.skills') }}" class="text-sm font-medium transition {{ request()->routeIs('portfolio.skills') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Keahlian</a>
                <a href="{{ route('portfolio.projects') }}" class="text-sm font-medium transition {{ request()->routeIs('portfolio.projects') || request()->routeIs('portfolio.projects.show') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Proyek</a>
                <a href="{{ route('portfolio.experience') }}" class="text-sm font-medium transition {{ request()->routeIs('portfolio.experience') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Pengalaman</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-medium transition {{ request()->routeIs('blog.*') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Blog</a>
                <a href="{{ route('portfolio.contact.show') }}" class="text-sm font-medium transition {{ request()->routeIs('portfolio.contact.show') ? 'text-cyan-300' : 'text-slate-300 hover:text-white' }}">Kontak</a>
                @if ($applicationLinks->isNotEmpty())
                <div class="relative" x-data="{ applicationsOpen: false }" @click.away="applicationsOpen = false" @keydown.escape.window="applicationsOpen = false">
                    <button type="button"
                            class="inline-flex items-center gap-1 text-sm font-medium text-slate-300 transition hover:text-white"
                            @click="applicationsOpen = !applicationsOpen"
                            :aria-expanded="applicationsOpen"
                            aria-haspopup="true">
                        Aplikasi
                        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': applicationsOpen }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                    <div x-show="applicationsOpen" x-cloak x-transition.origin.top.right
                         class="absolute right-0 top-full z-50 mt-3 w-56 rounded-xl border border-slate-700 bg-slate-900 p-2 shadow-xl shadow-slate-950/40">
                        @foreach ($applicationLinks as $applicationLink)
                            <a href="{{ $applicationLink->url }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-cyan-300">
                                {{ $applicationLink->name }}
                                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18m0 0v4.5M18 6l-7.5 7.5M10.5 7.5H6.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25V13.5"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                <a href="{{ route('search.index') }}" aria-label="Cari" title="Cari"
                   class="rounded-lg p-2 transition {{ request()->routeIs('search.index') ? 'text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </a>
                <a href="{{ route('portfolio.contact.show') }}" class="rounded-lg bg-gradient-to-r from-indigo-500 to-cyan-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-90">
                    Hubungi Saya
                </a>
            </div>

            {{-- Tombol hamburger (mobile) --}}
            <button type="button" class="rounded-lg p-2 text-slate-300 transition hover:bg-slate-800 hover:text-white lg:hidden"
                    @click="open = !open" :aria-expanded="open" aria-label="Buka menu navigasi">
                <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Menu mobile --}}
        <div x-show="open" x-cloak x-transition.origin.top class="border-t border-slate-800/80 bg-slate-900/95 px-4 pb-4 pt-2 backdrop-blur-md lg:hidden"
             @click.away="open = false">
            <a href="{{ route('portfolio.index') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('portfolio.index') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Beranda</a>
            <a href="{{ route('portfolio.skills') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('portfolio.skills') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Keahlian</a>
            <a href="{{ route('portfolio.projects') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('portfolio.projects*') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Proyek</a>
            <a href="{{ route('portfolio.experience') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('portfolio.experience') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Pengalaman</a>
            <a href="{{ route('blog.index') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('blog.*') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Blog</a>
            <a href="{{ route('portfolio.contact.show') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('portfolio.contact.show') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Kontak</a>
            @if ($applicationLinks->isNotEmpty())
            <div x-data="{ applicationsOpen: false }">
                <button type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
                        @click="applicationsOpen = !applicationsOpen"
                        :aria-expanded="applicationsOpen"
                        aria-haspopup="true">
                    Aplikasi
                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': applicationsOpen }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div x-show="applicationsOpen" x-cloak x-transition class="ml-3 border-l border-slate-700 pl-2">
                    @foreach ($applicationLinks as $applicationLink)
                        <a href="{{ $applicationLink->url }}" target="_blank" rel="noopener noreferrer"
                           @click="open = false"
                           class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-slate-800 hover:text-cyan-300">
                            {{ $applicationLink->name }}
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18m0 0v4.5M18 6l-7.5 7.5M10.5 7.5H6.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25V13.5"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            <a href="{{ route('search.index') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('search.index') ? 'bg-slate-800 text-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Cari</a>
            <a href="{{ route('portfolio.contact.show') }}" @click="open = false" class="mt-2 block rounded-lg bg-gradient-to-r from-indigo-500 to-cyan-500 px-3 py-2.5 text-center text-sm font-semibold text-white shadow-lg shadow-indigo-500/25">
                Hubungi Saya
            </a>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="border-t border-slate-800/60 bg-slate-950">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 py-10 sm:flex-row sm:px-6 lg:px-8">
            <p class="text-sm text-slate-400">
                &copy; {{ date('Y') }} Farhan Maulana Syidiq. Seluruh hak cipta dilindungi.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-5 text-sm sm:justify-end">
                <a href="{{ route('portfolio.skills') }}" class="text-slate-400 transition hover:text-cyan-300">Keahlian</a>
                <a href="{{ route('portfolio.projects') }}" class="text-slate-400 transition hover:text-cyan-300">Proyek</a>
                <a href="{{ route('portfolio.experience') }}" class="text-slate-400 transition hover:text-cyan-300">Pengalaman</a>
                <a href="{{ route('blog.index') }}" class="text-slate-400 transition hover:text-cyan-300">Blog</a>
                <a href="{{ route('portfolio.contact.show') }}" class="text-slate-400 transition hover:text-cyan-300">Kontak</a>
                <a href="{{ route('portfolio.cv') }}" class="text-slate-400 transition hover:text-cyan-300">CV</a>
            </div>
        </div>
    </footer>

    {{-- Lightbox global (sertifikat & proyek) --}}
    <div id="lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/90 p-4 backdrop-blur-sm [&:not(.hidden)]:flex" role="dialog" aria-modal="true" aria-label="Pratinjau gambar">
        <button type="button" data-lightbox-close aria-label="Tutup pratinjau"
                class="absolute right-4 top-4 rounded-full border border-slate-700 bg-slate-900/80 p-2.5 text-slate-300 transition hover:border-slate-500 hover:text-white">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
        <button type="button" data-lightbox-prev aria-label="Gambar sebelumnya"
                class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full border border-slate-700 bg-slate-900/80 p-2.5 text-slate-300 transition hover:border-slate-500 hover:text-white sm:left-6">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </button>
        <button type="button" data-lightbox-next aria-label="Gambar berikutnya"
                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full border border-slate-700 bg-slate-900/80 p-2.5 text-slate-300 transition hover:border-slate-500 hover:text-white sm:right-6">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
        <figure class="max-h-full w-full max-w-4xl" data-lightbox-close>
            <img data-lightbox-image src="" alt="" class="mx-auto max-h-[80vh] w-auto max-w-full rounded-2xl border border-slate-700 object-contain shadow-2xl">
            <figcaption data-lightbox-caption class="mt-3 text-center text-sm text-slate-400"></figcaption>
        </figure>
    </div>

    {{-- x-cloak: sembunyikan elemen Alpine sebelum JS termuat --}}
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
