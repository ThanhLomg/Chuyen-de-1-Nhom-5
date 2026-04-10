<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController
{
    public function index(Request $request)
    {
        // 3.4 Tối ưu truy vấn N+1 (Bắt buộc)
        $query = Course::with(['lessons', 'enrollments']);

        // 3.1 & 3.2 Tìm kiếm và Lọc theo Tên, Trạng thái, Giá
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sort_price')) {
            $query->orderBy('price', $request->sort_price);
        } else {
            $query->latest(); // Mặc định xếp mới nhất
        }

        $courses = $query->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    // Dùng CourseRequest thay vì Request thông thường
    public function store(CourseRequest $request)
    {
        // Chỉ lấy dữ liệu ĐÃ ĐƯỢC KIỂM TRA (Clean Code)
        $data = $request->validated(); 

        // Tự sinh slug từ tên khóa học
        $data['slug'] = Str::slug($data['name']);

        // Xử lý Upload Ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create($data);
        return redirect()->route('courses.index')->with('success', 'Thêm khóa học thành công!');
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']); // Cập nhật lại slug

        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image); // Xóa ảnh cũ
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);
        return redirect()->route('courses.index')->with('success', 'Cập nhật khóa học thành công!');
    }

    public function destroy(Course $course)
    {
        // Dùng Soft Delete (Xóa mềm) theo đúng yêu cầu đề bài
        $course->delete(); 
        return redirect()->route('courses.index')->with('success', 'Đã chuyển khóa học vào thùng rác!');
    }
}