<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index() 
    {
        $totalCourses = Course::count();
        $totalStudents = Student::count();

        // TÍNH DOANH THU: (Giá khóa học * Số lượng học viên đăng ký)
        $totalRevenue = Course::withCount('students')->get()->sum(function($course) {
            return $course->price * $course->students_count;
        });

        // TÌM KHÓA HỌC NHIỀU HỌC VIÊN NHẤT
        $topCourse = Course::withCount('students')
            ->having('students_count', '>', 0)
            ->orderBy('students_count', 'desc')
            ->first();

        // 5 KHÓA HỌC MỚI NHẤT
        $recentCourses = Course::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalCourses', 
            'totalStudents', 
            'totalRevenue', 
            'topCourse',
            'recentCourses'
        ));
    }
}