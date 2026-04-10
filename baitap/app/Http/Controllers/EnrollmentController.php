<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index() 
    {
        // Sử dụng Eager Loading để tránh N+1 Query
        $enrollments = Enrollment::with(['course', 'student'])->paginate(15);
        return view('enrollments.index', compact('enrollments'));
    }

    public function create() 
    {
        // Chỉ lấy những khóa học đã được Xuất bản (Dùng Scope published)
        $courses = Course::published()->get();
        $students = Student::all();
        
        return view('enrollments.create', compact('courses', 'students'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_id' => 'required|exists:students,id',
        ]);

        // Kiểm tra xem học viên này đã đăng ký khóa học này chưa
        $exists = Enrollment::where('course_id', $request->course_id)
                            ->where('student_id', $request->student_id)
                            ->exists();
        
        if ($exists) {
            return back()->with('danger', 'Học viên đã đăng ký khóa học này rồi!');
        }

        // Đăng ký thành công
        Enrollment::create($request->all());

        return redirect()->route('enrollments.index')
                         ->with('success', 'Đăng ký học viên vào khóa học thành công!');
    }

    public function destroy(int $id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return back()->with('success', 'Đã hủy đăng ký khóa học của học viên!');
    }
}