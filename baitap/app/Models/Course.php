<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes; // Yêu cầu Soft Delete

    protected $fillable = [
        'name', 'slug', 'price', 'description', 'image', 'status'
    ];

    // Tự động sinh slug nếu chưa có
    public function setSlugAttribute($value) {
        $this->attributes['slug'] = Str::slug($value);
    }

    // SCOPES - YÊU CẦU KỸ THUẬT
    public function scopePublished($query) {
        return $query->where('status', 'published');
    }

    public function scopeSearch($query, $search) {
        return $query->where('name', 'ilike', '%' . $search . '%');
    }

    public function scopePriceBetween($query, $min, $max) {
        return $query->whereBetween('price', [$min, $max]);
    }

    // Relationships (Quan hệ)
    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class); // 1 Course -> Nhiều Enrollment
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'enrollments'); // Many-to-Many
    }

    // Phương pháp tính doanh thu
    public function getRevenueAttribute() {
        // Tổng tiền các học viên đăng ký
        return $this->students()->pluck('name')->implode(', '); // Chỉ lấy info
        // Nếu cần tính tiền thực: 
        // return $this->price * $this->students()->count(); 
    }

    // Phương pháp đếm học viên
    public function getStudentCountAttribute() {
        return $this->students()->count();
    }
}

