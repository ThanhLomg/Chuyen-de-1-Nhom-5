<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Danh sách người dùng (chỉ customer) với tìm kiếm và lọc
     */
    public function index(Request $request): View
    {
        $query = User::customers()
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($subQuery) {
                $subQuery->where('payment_status', 'paid'); // hoặc where('status', 'delivered')
            }], 'total')
            ->latest();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Chi tiết một người dùng
     */
    public function show(int $id): View
    {
        $user = User::with(['orders' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        // Tổng chi tiêu của user (có thể dùng accessor hoặc tính riêng)
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    /**
     * Khóa / mở khóa tài khoản
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->is_admin) {
            return back()->with('error', 'Không thể khóa tài khoản admin.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $action = $user->is_active ? 'mở khóa' : 'khóa';
        return back()->with('success', "Đã {$action} tài khoản thành công.");
    }
}