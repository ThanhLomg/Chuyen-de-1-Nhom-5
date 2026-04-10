<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CourseController extends Controller
{
    /**
     * Hiển thị danh sách khóa học (Tìm kiếm, Lọc, Phân trang)
     */
    public function index(Request $request)
    {
        // 1. Bắt đầu query builder với Eager Loading để tránh N+1 Query
        $query = Course::with(['lessons', 'students']);

        // 2. Tìm kiếm theo tên khóa học (Sử dụng toán tử like)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 3. Lọc theo trạng thái (draft/published)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 4. Lọc theo khoảng giá
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->input('min_price'), $request->input('max_price')]);
        } elseif ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        // 5. Lấy dữ liệu mới nhất và phân trang
        $courses = $query->latest()->paginate(10);

        // Giữ lại các tham số trên URL (query string) khi người dùng bấm sang trang 2, 3...
        $courses->appends($request->all());

        return view('courses.index', compact('courses'));
    }

    /**
     * Hiển thị form thêm mới khóa học
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Xử lý lưu khóa học mới vào CSDL
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();

        // TỰ ĐỘNG SINH SLUG TỪ TÊN KHÓA HỌC
        $validated['slug'] = Str::slug($validated['name']); // <-- THÊM DÒNG NÀY

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        } else {
            $validated['image'] = 'default.jpg';
        }

        Course::create($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'Thêm khóa học thành công!');
    }

    /**
     * Hiển thị chi tiết một khóa học
     */
    public function show(int $id)
    {
        // Lấy chi tiết khóa học, load sẵn lessons để hiển thị nếu cần
        $course = Course::with('lessons')->findOrFail($id);
        
        return view('courses.show', compact('course'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin khóa học
     */
    public function edit(int $id)
    {
        $course = Course::findOrFail($id);
        
        return view('courses.edit', compact('course'));
    }

    /**
     * Xử lý cập nhật thông tin khóa học
     */
    public function update(StoreCourseRequest $request, int $id)
    {
        $course = Course::findOrFail($id);
        $validated = $request->validated();

        // TỰ ĐỘNG CẬP NHẬT SLUG NẾU ĐỔI TÊN
        $validated['slug'] = Str::slug($validated['name']); // <-- THÊM DÒNG NÀY

        // ... code xử lý ảnh giữ nguyên ...

        // Xử lý cập nhật ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            // Tối ưu: Xóa file ảnh cũ khỏi hệ thống để tiết kiệm dung lượng
            if ($course->image && $course->image !== 'default.jpg') {
                Storage::disk('public')->delete($course->image);
            }

            // Lưu file ảnh mới
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        // Thực thi Update
        $course->update($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'Cập nhật thông tin khóa học thành công!');
    }

    /**
     * Xóa khóa học (Xóa mềm - Soft Delete)
     */
    public function destroy(int $id)
    {
        $course = Course::findOrFail($id);
        
        // Vì Model Course đã có `use SoftDeletes;`, hàm delete() này sẽ chỉ cập nhật cột `deleted_at`
        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'Đã xóa khóa học an toàn!');
    }
}