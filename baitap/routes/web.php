<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Quản lý Khóa học (CRUD đầy đủ)
Route::resource('courses', CourseController::class);

// Quản lý Bài học (Nested Resource - Bài học thuộc về 1 Khóa học)
Route::prefix('courses/{course}/lessons')->name('lessons.')->group(function () {
    Route::get('/', [LessonController::class, 'index'])->name('index');
    Route::post('/', [LessonController::class, 'store'])->name('store');
    Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('destroy');
});

// Quản lý Đăng ký học (Enrollments)
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store', 'destroy']);