<?php

namespace App\Providers;

use App\Models\ApplicationLink;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tanggal (translatedFormat) mengikuti locale aplikasi, cth: "04 Oktober 2026".
        Carbon::setLocale(config('app.locale', 'id'));

        View::composer('components.public-layout', function ($view): void {
            $view->with(
                'applicationLinks',
                ApplicationLink::query()->active()->ordered()->get(),
            );
        });
    }
}
