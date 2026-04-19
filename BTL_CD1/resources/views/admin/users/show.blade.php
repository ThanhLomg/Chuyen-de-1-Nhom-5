@extends('layouts.admin')

@section('title', 'Chi tiết người dùng: ' . $user->name)
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary">Dashboard</a> /
    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-primary">Người dùng</a> /
    <span>{{ $user->name }}</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Cột trái: Thông tin cá nhân --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="w-24 h-24 rounded-full bg-primary-light text-white flex items-center justify-center text-3xl font-medium mx-auto">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-semibold mt-4">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>

            <div class="mt-4">
                <span class="px-3 py-1 rounded-full text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Đang hoạt động' : 'Bị khóa' }}
                </span>
                @if($user->is_admin)
                    <span class="ml-2 px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">Admin</span>
                @endif
            </div>

            <dl class="mt-6 text-left space-y-3">
                <div>
                    <dt class="text-sm text-gray-500">Số điện thoại</dt>
                    <dd>{{ $user->phone ?? 'Chưa cập nhật' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Địa chỉ</dt>
                    <dd>{{ $user->address ?? 'Chưa cập nhật' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Ngày tham gia</dt>
                    <dd>{{ $user->created_at->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Email xác thực</dt>
                    <dd>
                        @if($user->email_verified_at)
                            <span class="text-green-600">✓ Đã xác thực ({{ $user->email_verified_at->format('d/m/Y') }})</span>
                        @else
                            <span class="text-yellow-600">Chưa xác thực</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Tổng đơn hàng</dt>
                    <dd>{{ $user->orders->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Tổng chi tiêu</dt>
                    <dd class="font-medium text-primary">{{ number_format($user->total_spent, 0, ',', '.') }}đ</dd>
                </div>
            </dl>

            @if(!$user->is_admin)
            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="mt-6">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 rounded-md transition-colors"
                        onclick="return confirm('Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?')">
                    {{ $user->is_active ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Cột phải: Lịch sử đơn hàng --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-lg mb-4">Lịch sử đơn hàng</h3>

            @if($user->orders->isEmpty())
                <p class="text-gray-500 text-center py-8">Người dùng chưa có đơn hàng nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b text-gray-600">
                            <tr>
                                <th class="pb-2 text-left">Mã ĐH</th>
                                <th class="pb-2 text-left">Ngày đặt</th>
                                <th class="pb-2 text-right">Tổng tiền</th>
                                <th class="pb-2 text-left">Trạng thái</th>
                                <th class="pb-2 text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders as $order)
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="py-3 font-mono">{{ $order->order_number }}</td>
                                <td class="py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 text-right font-medium">{{ $order->formatted_total }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="text-primary hover:underline">Xem</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection