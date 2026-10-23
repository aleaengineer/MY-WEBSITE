@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($static as $url)
    <url>
        <loc>{{ $url }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($projects as $project)
    <url>
        <loc>{{ route('portfolio.projects.show', $project->slug) }}</loc>
        @if ($project->updated_at)
        <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
        @endif
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach ($experiences ?? [] as $experience)
    <url>
        <loc>{{ $experience['url'] }}</loc>
        @if (! empty($experience['lastmod']))
        <lastmod>{{ $experience['lastmod'] }}</lastmod>
        @endif
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach ($posts as $post)
    @php
        $lastmod = $post->updated_at ?? $post->published_at;
    @endphp
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        @if ($lastmod)
        <lastmod>{{ $lastmod->toAtomString() }}</lastmod>
        @endif
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
