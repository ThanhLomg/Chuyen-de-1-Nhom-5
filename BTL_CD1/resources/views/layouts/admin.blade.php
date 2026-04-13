<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard') | FurniShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <aside class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-5 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                    <span class="text-blue-400">Furni</span>Admin
                </a>
            </div>
            <nav class="flex-1 py-5 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    Dashboard
                </a>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                        @csrf
                        <button class="text-gray-400 hover:text-white">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </aside>
        <main class="ml-64 flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>