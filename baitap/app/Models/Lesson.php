<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'course_id', 'title', 'content', 'video_url', 'order'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
