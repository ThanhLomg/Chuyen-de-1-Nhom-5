<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa thông tin cá nhân
     */
    public function edit(): View
    {
        return view('account.profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|regex:/^(0[3|5|7|8|9])+([0-9]{8})$/',
            'address'          => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:password|current_password',
            'password'         => 'nullable|min:8|confirmed',
        ], [
            'phone.regex'                => 'Số điện thoại không hợp lệ (VD: 0912345678).',
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'password.min'               => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed'         => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Cập nhật thông tin cơ bản
        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        // Nếu có nhập mật khẩu mới
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('account.profile')
            ->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }
}