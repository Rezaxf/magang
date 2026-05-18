<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade; // TAMBAH INI
use App\View\Components\AdminLayout; // TAMBAH INI

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
        // Pagination Tailwind
        Paginator::useTailwind();

        // REGISTER ADMIN LAYOUT
        Blade::component('admin-layout', AdminLayout::class);
    }
}