<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pdf;
use App\Models\Message;
use App\Models\Feedback;

class TeacherController extends Controller
{
    public function welcome()
    {
        $teacher = Auth::user();
        
        // nr total de studenti coordonati
        $studentsCount = User::where('teacher_id', $teacher->id)->count();
        
        // nr total de lucrari primite
        $pdfsCount = Pdf::whereHas('user', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->count();
        
        // nr total de feedback-uri date
        $feedbacksCount = Feedback::where('teacher_id', $teacher->id)->count();
        
        // nr total de mesaje necitite de la studenti
        $unreadMessagesCount = Message::where('teacher_id', $teacher->id)
            ->where('sender_id', '!=', $teacher->id)
            ->where('is_read', false)
            ->count();
            
        return view('teacher.welcome', compact('studentsCount', 'pdfsCount', 'feedbacksCount', 'unreadMessagesCount'));
    }
} 