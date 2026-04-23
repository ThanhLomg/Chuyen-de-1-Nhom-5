@extends('layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Thông tin tài khoản</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('account.profile.update') }}">
            @csrf
            @method('PATCH')

            {{-- Thông tin cơ bản --}}
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                           class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Email không thể thay đổi.</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Số điện thoại
                    </label>
                    <input type="tel" name="phone" id="phone"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="VD: 0912345678"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Địa chỉ
                    </label>
                    <input type="text" name="address" id="address"
                           value="{{ old('address', $user->address) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Đổi mật khẩu --}}
            <hr class="my-6">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">Đổi mật khẩu</h3>
            <p class="text-sm text-gray-500 mb-4">Để trống nếu bạn không muốn thay đổi mật khẩu.</p>

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mật khẩu hiện tại
                    </label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mật khẩu mới
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Xác nhận mật khẩu mới
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Nút submit - SỬA MÀU RÕ RÀNG --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md transition-colors shadow-sm">
                    Cập nhật thông tin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection