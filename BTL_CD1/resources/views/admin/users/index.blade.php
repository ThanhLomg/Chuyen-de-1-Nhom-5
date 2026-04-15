@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <span>Người dùng</span>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Danh sách khách hàng</h2>
    </div>

    {{-- Form lọc --}}
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-sm text-gray-600 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Tên, email, số điện thoại..."
                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Trạng thái</label>
                <select name="status" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Bị khóa</option>
                </select>
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark text-sm">
                Lọc
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="border border-gray-300 bg-white px-4 py-2 rounded-md hover:bg-gray-50 text-sm">
                Reset
            </a>
        </form>
    </div>

    {{-- Bảng danh sách --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-600">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Khách hàng</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Số điện thoại</th>
                    <th class="px-6 py-3">Đơn hàng</th>
                    <th class="px-6 py-3">Tổng chi tiêu</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $user->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-light text-white flex items-center justify-center text-xs font-medium">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $user->orders_count }}</td>
                    <td class="px-6 py-4 font-medium">{{ number_format($user->total_spent, 0, ',', '.') }}đ</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Hoạt động' : 'Bị khóa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="text-primary hover:underline">Xem</a>
                        @if(!$user->is_admin)
                            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-{{ $user->is_active ? 'red' : 'green' }}-600 hover:underline"
                                        onclick="return confirm('Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?')">
                                    {{ $user->is_active ? 'Khóa' : 'Mở khóa' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">Không có người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t">
        {{ $users->links() }}
    </div>
</div>
@endsection