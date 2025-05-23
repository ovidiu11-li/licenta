<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class StudentMessageController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $teacher = $student->teacher;
        $messages = [];
        if ($teacher) {
            $messages = Message::where('student_id', $student->id)
                ->where('teacher_id', $teacher->id)
                ->orderBy('created_at')
                ->get();
        }
        return view('student.messages', compact('student', 'teacher', 'messages'));
    }

    public function store(Request $request)
    {
        $student = Auth::user();
        $teacher = $student->teacher;
        if (!$teacher) {
            return redirect()->back()->withErrors('Nu ai profesor asociat.');
        }
        $data = $request->validate([
            'content' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('messages', 'private');
        }
        \App\Models\Message::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'sender_id' => $student->id,
            'content' => $data['content'],
            'pdf_path' => $pdfPath,
        ]);
        return redirect()->back()->with('success', 'Mesaj trimis!');
    }
} 