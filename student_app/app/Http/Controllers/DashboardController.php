<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class DashboardController
{
    public function index()
    {
        // Lấy các con số thống kê từ Database
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $draftCourses = Course::where('status', 'draft')->count();
        $averagePrice = Course::avg('price') ?? 0;

        // Truyền dữ liệu ra view
        return view('dashboard', compact(
            'totalCourses', 
            'publishedCourses', 
            'draftCourses', 
            'averagePrice'
        ));
    }
}