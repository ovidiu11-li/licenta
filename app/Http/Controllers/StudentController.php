<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function uploadPdf(Request $request)
    {
        \Log::info('Upload PDF', ['user_id' => Auth::id()]);
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('pdf');
        $path = $file->store('pdfs', 'private');

        // Check if student already has a current PDF
        $existingPdf = Pdf::where('user_id', Auth::id())
            ->where('is_current', true)
            ->first();

        if ($existingPdf) {
            // Mark existing PDF as not current
            $existingPdf->update(['is_current' => false]);
            
            // Create new version
            $newVersion = Pdf::create([
                'user_id' => Auth::id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $path,
                'version' => $existingPdf->version + 1,
                'parent_pdf_id' => $existingPdf->getRootPdf()->id,
                'is_current' => true,
                'token' => Str::random(40),
            ]);
            \Log::info('Created new PDF version', ['pdf_id' => $newVersion->id]);
        } else {
            // First PDF upload
            $pdf = Pdf::create([
                'user_id' => Auth::id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $path,
                'version' => 1,
                'is_current' => true,
                'token' => Str::random(40),
            ]);
            \Log::info('Created first PDF', ['pdf_id' => $pdf->id]);
        }

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
        })->with(['user', 'versions'])->get();
        
        // Group PDFs by their root PDF to show versions together
        $groupedPdfs = $pdfs->groupBy(function($pdf) {
            return $pdf->getRootPdf()->id;
        });
        
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
        return view('teacher.dashboard', compact('groupedPdfs', 'unreadCount'));
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
        $student = Auth::user();
        $pdfs = \App\Models\Pdf::where('user_id', $student->id)
            ->orderBy('version', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

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
        return view('student.dashboard', compact('pdfs', 'unreadCount'));
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

    public function deletePdfVersion($pdfId)
    {
        $pdf = Pdf::findOrFail($pdfId);

        // Only allow students to delete their own PDF versions
        if ($pdf->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Dacă e root și are copii, nu permite ștergerea
        if ($pdf->parent_pdf_id === null && $pdf->versions()->count() > 0) {
            return redirect()->back()->withErrors('Nu poți șterge prima versiune dacă există versiuni ulterioare.');
        }
        // Dacă e root și nu are copii, permite ștergerea

        // Dacă ștergi versiunea curentă, setează versiunea anterioară ca is_current
        if ($pdf->is_current) {
            $previous = Pdf::where('parent_pdf_id', $pdf->parent_pdf_id ?? $pdf->id)
                ->where('id', '!=', $pdf->id)
                ->orderByDesc('version')
                ->first();
            if (!$previous && $pdf->parent_pdf_id) {
                // Dacă nu există altă versiune, root-ul devine curent
                $previous = Pdf::find($pdf->parent_pdf_id);
            }
            if ($previous) {
                $previous->update(['is_current' => true]);
            }
        }

        // Delete the file from storage
        Storage::disk('private')->delete($pdf->stored_name);
        // Delete the database record
        $pdf->delete();

        return redirect()->back()->with('success', 'Versiunea PDF a fost ștearsă cu succes.');
    }

    public function downloadByToken($token)
    {
        $pdf = Pdf::where('token', $token)->firstOrFail();
        return \Storage::disk('private')->download($pdf->stored_name, $pdf->original_name);
    }

    public function viewByToken($token)
    {
        $pdf = Pdf::where('token', $token)->firstOrFail();
        $path = \Storage::disk('private')->path($pdf->stored_name);
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$pdf->original_name.'"'
        ]);
    }

    public function welcome()
    {
        $student = Auth::user();
        $unreadCount = 0;
        if ($student->teacher) {
            $unreadCount = \App\Models\Message::where('student_id', $student->id)
                ->where('teacher_id', $student->teacher->id)
                ->where('sender_id', $student->teacher->id)
                ->where('is_read', false)
                ->count();
        }
        return view('student.welcome', compact('unreadCount'));
    }
} 