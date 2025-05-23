<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pdf;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('pdf');
        $path = $file->store('pdfs', 'private');

        Pdf::create([
            'user_id' => Auth::id(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $path,
        ]);

        return redirect()->back()->with('success', 'PDF încărcat cu succes!');
    }

    public function download(\App\Models\Pdf $pdf)
    {
        
        return Storage::disk('private')->download($pdf->stored_name, $pdf->original_name);
    }

    public function view(\App\Models\Pdf $pdf)
    {
        $path = \Storage::disk('private')->path($pdf->stored_name);
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$pdf->original_name.'"'
        ]);
    }

    public function teacherDashboard()
    {
        $teacher = Auth::user();
        $pdfs = \App\Models\Pdf::whereHas('user', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('user')->get();
        $unreadCount = 0;
        if (auth()->user()->role === 'student') {
            $student = auth()->user();
            $teacher = $student->teacher;
            if ($teacher) {
                $unreadCount = \App\Models\Message::where('student_id', $student->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('sender_id', $teacher->id)
                    ->where('is_read', false)
                    ->count();
            }
        }
        return view('teacher.dashboard', compact('pdfs', 'unreadCount'));
    }

    public function downloadMessagePdf($pdfPath)
    {
        $fullPath = 'messages/' . $pdfPath;
        if (!\Storage::disk('private')->exists($fullPath)) {
            abort(404);
        }
        // filename random
        $filename = basename($pdfPath);
        return \Storage::disk('private')->download($fullPath, $filename);
    }

    public function studentDashboard()
    {
        $unreadCount = 0;
        if (auth()->user()->role === 'student') {
            $student = auth()->user();
            $teacher = $student->teacher;
            if ($teacher) {
                $unreadCount = \App\Models\Message::where('student_id', $student->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('sender_id', $teacher->id)
                    ->where('is_read', false)
                    ->count();
            }
        }
        return view('student.dashboard', compact('unreadCount'));
    }

    // arata formularul de feedback pentru un pdf (profesor)
    public function showFeedbackForm($pdfId)
    {
        $pdf = \App\Models\Pdf::with('user')->findOrFail($pdfId);
        $student = $pdf->user;
        return view('teacher.feedback_form', compact('pdf', 'student'));
    }

    // stocheaza feedbackul de la profesor
    public function storeFeedback(Request $request, $pdfId)
    {
        $pdf = \App\Models\Pdf::with('user')->findOrFail($pdfId);
        $student = $pdf->user;
        $teacher = auth()->user();
        $data = $request->validate([
            'grade' => 'required|integer|min:1|max:10',
            'message' => 'required|string|max:2000',
        ]);
        \App\Models\Feedback::updateOrCreate(
            [
                'pdf_id' => $pdf->id,
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
            ],
            [
                'grade' => $data['grade'],
                'message' => $data['message'],
            ]
        );
        return redirect()->route('teacher.dashboard')->with('success', 'Feedback salvat cu succes!');
    }

    // pagina de feedback pentru student
    public function feedbackPage()
    {
        $student = auth()->user();
        $feedbacks = \App\Models\Feedback::where('student_id', $student->id)
            ->with(['pdf', 'teacher'])
            ->orderByDesc('created_at')
            ->get();
        return view('student.feedback', compact('feedbacks'));
    }

    // pagina de feedback-uri pentru profesor
    public function teacherFeedbacksPage()
    {
        $teacher = auth()->user();
        $feedbacks = \App\Models\Feedback::where('teacher_id', $teacher->id)
            ->with(['pdf', 'student'])
            ->orderByDesc('created_at')
            ->get();
        return view('teacher.feedbacks', compact('feedbacks'));
    }
} 