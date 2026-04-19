<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Đường dẫn mặc định sau khi đăng nhập
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Chia sẻ biến $cartCount cho tất cả view
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $cart = app(CartService::class);
                $view->with('cartCount', $cart->count());
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}