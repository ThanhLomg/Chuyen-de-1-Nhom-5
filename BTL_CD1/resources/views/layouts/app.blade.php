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
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    <header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="sticky top-0 z-50 bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">
                        <span class="text-primary">Furni</span>Shop
                    </a>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-primary font-medium">Sản phẩm</a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-700 hover:text-primary font-medium flex items-center">
                            Danh mục
                            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50">
                            @foreach(\App\Models\Category::active()->get() as $cat)
                                <a href="{{ route('categories.show', $cat->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Khuyến mãi</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <button @click="searchOpen = !searchOpen" class="text-gray-600 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount ?? 0 }}</span>
                    </a>
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary">
                            <span class="mr-1">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('account.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đơn hàng của tôi</a>
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thông tin tài khoản</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">Đăng ký</a>
                    </div>
                    @endauth
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-white border-t py-4 px-4">
            <nav class="flex flex-col space-y-3">
                <a href="{{ route('products.index') }}" class="text-gray-700">Sản phẩm</a>
                @foreach(\App\Models\Category::active()->take(5)->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="text-gray-700">{{ $cat->name }}</a>
                @endforeach
                <a href="#" class="text-gray-700">Khuyến mãi</a>
                @guest
                <div class="pt-3 border-t">
                    <a href="{{ route('login') }}" class="block text-primary font-medium">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="block mt-2 bg-primary text-white text-center py-2 rounded">Đăng ký</a>
                </div>
                @endguest
            </nav>
        </div>
        <!-- Search overlay -->
        <div x-show="searchOpen" @click.away="searchOpen = false" class="absolute top-16 left-0 w-full bg-white shadow-lg p-4 z-40">
            <div class="container mx-auto relative">
                <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm..." class="w-full px-4 py-3 border rounded-lg focus:ring-primary focus:border-primary">
                <div id="search-results" class="hidden absolute top-full left-0 w-full bg-white border rounded-lg shadow-lg mt-1 z-50 max-h-96 overflow-y-auto"></div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">FurniShop</h3>
                    <p class="text-sm">Nội thất cao cấp, phong cách sống hiện đại.</p>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Hỗ trợ khách hàng</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách bảo hành</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Về chúng tôi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Giới thiệu</a></li>
                        <li><a href="#" class="hover:text-white">Liên hệ</a></li>
                        <li><a href="#" class="hover:text-white">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-4">Theo dõi chúng tôi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white">Facebook</a>
                        <a href="#" class="hover:text-white">Instagram</a>
                        <a href="#" class="hover:text-white">Zalo</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm">
                &copy; {{ date('Y') }} FurniShop. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>