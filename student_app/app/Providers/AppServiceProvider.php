<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Thêm thư viện này

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Báo cho Laravel sử dụng giao diện phân trang của Bootstrap
        Paginator::useBootstrapFive(); 
    }
}