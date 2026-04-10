<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'content'];

    // Bài học thuộc về 1 Khóa học (belongsTo)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}