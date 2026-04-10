<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEnrollmentRequest extends FormRequest
{
    public function rules() {
        return [
            'course_id' => 'required|exists:courses,id',
            'student' => 'required|exists:students,id',
        ];
    }
}
