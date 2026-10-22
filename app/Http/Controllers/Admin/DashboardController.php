<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Ringkasan panel admin.
     */
    public function index(): View
    {
        $analyticsStart = now()->subDays(29)->startOfDay();

        return view('admin.dashboard', [
            'totalProjects' => Project::count(),
            'featuredProjects' => Project::featured()->count(),
            'totalPosts' => Post::count(),
            'publishedPosts' => Post::published()->count(),
            'totalMessages' => Message::count(),
            'unreadMessages' => Message::unread()->count(),
            'latestMessages' => Message::orderByDesc('id')->take(5)->get(),
            'totalExperiences' => Experience::count(),
            'totalCertifications' => Certification::count(),
            'totalEducations' => Education::count(),
            'todayPageViews' => PageView::query()->whereDate('visit_date', today())->sum('views'),
            'monthlyPageViews' => PageView::query()->where('visit_date', '>=', $analyticsStart->toDateString())->sum('views'),
            'popularPages' => PageView::query()
                ->where('visit_date', '>=', $analyticsStart->toDateString())
                ->select('path', DB::raw('SUM(views) as total_views'))
                ->groupBy('path')
                ->orderByDesc('total_views')
                ->limit(5)
                ->get(),
        ]);
    }
}
