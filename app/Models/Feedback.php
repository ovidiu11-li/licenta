<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'pdf_id',
        'teacher_id',
        'student_id',
        'grade',
        'message',
    ];

    public function pdf()
    {
        return $this->belongsTo(Pdf::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 