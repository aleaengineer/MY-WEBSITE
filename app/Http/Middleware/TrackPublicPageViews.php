<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicPageViews
{
    private const TRACKED_ROUTES = [
        'portfolio.index',
        'portfolio.skills',
        'portfolio.projects',
        'portfolio.projects.show',
        'portfolio.experience',
        'portfolio.experience.show',
        'portfolio.contact.show',
        'portfolio.cv',
        'blog.index',
        'blog.show',
        'search.index',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $routeName = $request->route()?->getName();
        $contentType = $response->headers->get('Content-Type', '');

        if (
            $request->isMethod('GET')
            && in_array($routeName, self::TRACKED_ROUTES, true)
            && $response->isSuccessful()
            && str_contains($contentType, 'text/html')
        ) {
            $now = now();

            DB::table('page_views')->upsert(
                [[
                    'visit_date' => $now->toDateString(),
                    'path' => '/'.ltrim($request->path(), '/'),
                    'views' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]],
                ['visit_date', 'path'],
                [
                    'views' => DB::raw('page_views.views + 1'),
                    'updated_at' => $now,
                ],
            );
        }

        return $response;
    }
}
