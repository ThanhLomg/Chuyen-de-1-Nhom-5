<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(int $courseId) 
    {
        $course = Course::findOrFail($courseId);
        
        // Lấy danh sách bài học, sắp xếp theo thứ tự (order)
        $lessons = Lesson::where('course_id', $courseId)
            ->orderBy('order', 'asc')
            ->get();
        
        return view('lessons.index', compact('lessons', 'course'));
    }

    public function store(Request $request, int $courseId) 
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'order' => 'required|integer',
        ]);

        // Thêm bài học mới
        Lesson::create([
            'course_id' => $courseId,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'video_url' => $validated['video_url'],
            'order' => $validated['order'],
        ]);

        return back()->with('success', 'Thêm bài học thành công!');
    }

    public function destroy(int $courseId, int $lessonId) 
    {
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);
        $lesson->delete();

        return back()->with('success', 'Đã xóa bài học!');
    }
}