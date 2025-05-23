<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class TeacherMessageController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $students = $teacher->students;
        $selectedStudent = null;
        $messages = collect();
        if ($request->has('student')) {
            $selectedStudent = $students->where('id', $request->student)->first();
            if ($selectedStudent) {
                $messages = Message::where('student_id', $selectedStudent->id)
                    ->where('teacher_id', $teacher->id)
                    ->orderBy('created_at')
                    ->get();
            }
        }
        return view('teacher.messages', compact('teacher', 'students', 'selectedStudent', 'messages'));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();
        $students = $teacher->students;
        $studentId = $request->input('student_id');
        $selectedStudent = $students->where('id', $studentId)->first();
        if (!$selectedStudent) {
            return redirect()->back()->withErrors('Studentul selectat nu este valid.');
        }
        $data = $request->validate([
            'content' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('messages', 'private');
        }
        Message::create([
            'student_id' => $selectedStudent->id,
            'teacher_id' => $teacher->id,
            'sender_id' => $teacher->id,
            'content' => $data['content'],
            'pdf_path' => $pdfPath,
        ]);
        return redirect()->route('teacher.messages', ['student' => $selectedStudent->id])->with('success', 'Mesaj trimis!');
    }
} 