<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes; // Bắt buộc theo yêu cầu đề

    protected $fillable = ['name', 'slug', 'price', 'description', 'image', 'status'];

    // 1 Khóa học có nhiều Bài học (hasMany)
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    // 1 Khóa học có nhiều lượt đăng ký
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Quan hệ Many-to-Many với Student (belongsToMany)
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments');
    }

    // Yêu cầu 3.5: Scopes nâng cao
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}