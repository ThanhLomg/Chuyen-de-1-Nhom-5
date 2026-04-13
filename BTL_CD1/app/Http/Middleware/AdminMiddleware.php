<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Thêm dòng này

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Thay auth() bằng Auth Facade
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
        }

        return $next($request);
    }
}