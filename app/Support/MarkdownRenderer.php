<?php

namespace App\Support;

use Illuminate\Support\Str;

class MarkdownRenderer
{
    /**
     * Render Markdown dengan opsi aman.
     *
     * Setiap halaman sudah memiliki satu H1 (judul halaman), sehingga heading
     * H1 yang ditulis di dalam konten diturunkan menjadi H2 agar struktur
     * heading tetap benar untuk SEO dan aksesibilitas.
     */
    public static function toHtml(?string $markdown): string
    {
        $html = Str::markdown((string) $markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return preg_replace('/<(\/?)h1(\s[^>]*)?>/i', '<$1h2$2>', $html) ?? $html;
    }
}
