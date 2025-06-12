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
        
        // Numărul total de studenți coordonați
        $studentsCount = User::where('teacher_id', $teacher->id)->count();
        
        // Numărul total de lucrări primite
        $pdfsCount = Pdf::whereHas('user', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->count();
        
        // Numărul de feedback-uri date
        $feedbacksCount = Feedback::where('teacher_id', $teacher->id)->count();
        
        // Numărul de mesaje necitite de la studenți
        $unreadMessagesCount = Message::where('teacher_id', $teacher->id)
            ->where('sender_id', '!=', $teacher->id)
            ->where('is_read', false)
            ->count();
            
        return view('teacher.welcome', compact('studentsCount', 'pdfsCount', 'feedbacksCount', 'unreadMessagesCount'));
    }
} 