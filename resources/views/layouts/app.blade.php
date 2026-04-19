<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FurniShop') - Nội thất cao cấp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    <header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="sticky top-0 z-50 bg-white shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">
                        <span class="text-blue-600">Furni</span>Shop
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Sản phẩm</a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-700 hover:text-blue-600 font-medium flex items-center transition-colors">
                            Danh mục
                            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                            @foreach(\App\Models\Category::active()->get() as $cat)
                                <a href="{{ route('categories.show', $cat->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Khuyến mãi</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Liên hệ</a>
                </nav>

                {{-- Right Actions --}}
                <div class="flex items-center space-x-1 sm:space-x-3">
                    {{-- Search Button --}}
                    <button @click="searchOpen = !searchOpen" class="p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="cart-count absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    </a>

                    {{-- Authentication --}}
                    @guest
                        {{-- Desktop: hiển thị trực tiếp --}}
                        <div class="hidden sm:flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded-lg transition-colors">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                Đăng ký
                            </a>
                        </div>
                        {{-- Mobile: icon user (tạm) hoặc để menu hamburger xử lý --}}
                    @endguest

                    @auth
                        {{-- Admin Link --}}
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="hidden md:inline-flex items-center px-3 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors shadow-sm">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Admin
                            </a>
                        @endif

                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                <span class="hidden sm:inline mr-2 text-sm font-medium">{{ auth()->user()->name }}</span>
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                    Đơn hàng của tôi
                                </a>
                                <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                    Thông tin tài khoản
                                </a>
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    {{-- Mobile Menu Toggle --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition class="lg:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4 space-y-1">
                <a href="{{ route('products.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Sản phẩm</a>
                @foreach(\App\Models\Category::active()->take(6)->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                        {{ $cat->name }}
                    </a>
                @endforeach
                <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Khuyến mãi</a>
                <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Liên hệ</a>

                @guest
                    <div class="pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block w-full px-4 py-2 text-center text-blue-600 font-medium border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="block w-full px-4 py-2 text-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Đăng ký
                        </a>
                    </div>
                @endguest

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block w-full px-4 py-2 text-center bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-900 transition-colors">
                            Trang Quản Trị
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Search Overlay --}}
        <div x-show="searchOpen" @click.away="searchOpen = false" x-transition class="absolute top-full left-0 w-full bg-white shadow-lg border-t z-40">
            <div class="container mx-auto px-4 py-4">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm..." 
                           class="w-full px-5 py-3 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-shadow">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div id="search-results" class="hidden mt-3 bg-white rounded-xl border border-gray-100 shadow-lg max-h-96 overflow-y-auto divide-y divide-gray-100"></div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8 mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">FurniShop</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Nội thất cao cấp, phong cách sống hiện đại. Mang đến không gian sống hoàn hảo cho gia đình bạn.</p>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Hỗ trợ khách hàng</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Chính sách bảo hành</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Về chúng tôi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Giới thiệu</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Liên hệ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Theo dõi chúng tôi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Facebook</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Instagram</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Zalo</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} FurniShop. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>