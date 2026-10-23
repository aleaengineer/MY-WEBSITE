@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} — Blog</title>
        <link>{{ route('blog.index') }}</link>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />
        <description>Catatan, pengalaman, dan pembelajaran seputar jaringan, DevOps, dan web development.</description>
        <language>id</language>
        @foreach ($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('blog.show', $post->slug) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
            <description>{{ $post->excerpt_text }}</description>
            @if ($post->published_at)
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            @endif
            @if ($post->category)
            <category>{{ $post->category }}</category>
            @endif
        </item>
        @endforeach
    </channel>
</rss>
