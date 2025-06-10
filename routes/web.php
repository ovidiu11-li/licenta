<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminPdfController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StudentMessageController;
use App\Http\Controllers\TeacherMessageController;
use App\Http\Controllers\TeacherPdfController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'teacher':
                return redirect('/teacher/dashboard');
            case 'student':
            default:
                return redirect('/student/dashboard');
        }
    }
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/teacher/dashboard', [App\Http\Controllers\StudentController::class, 'teacherDashboard'])->name('teacher.dashboard');

    Route::get('/student/dashboard', [App\Http\Controllers\StudentController::class, 'studentDashboard'])->name('student.dashboard');

    Route::get('/teacher/feedback/{pdf}', [App\Http\Controllers\StudentController::class, 'showFeedbackForm'])->name('teacher.feedback.form');
    Route::post('/teacher/feedback/{pdf}', [App\Http\Controllers\StudentController::class, 'storeFeedback'])->name('teacher.feedback.store');

    Route::get('/student/feedback', [App\Http\Controllers\StudentController::class, 'feedbackPage'])->name('student.feedback');

    Route::get('/teacher/feedbacks', [App\Http\Controllers\StudentController::class, 'teacherFeedbacksPage'])->name('teacher.feedbacks');
});

Route::post('/student/upload', [StudentController::class, 'uploadPdf'])->name('student.upload');
Route::delete('/student/pdf/{pdf}', [StudentController::class, 'deletePdfVersion'])->name('student.pdf.delete');
Route::post('/admin/pdf/{pdf}/rename', [AdminPdfController::class, 'rename'])->name('admin.pdf.rename');
Route::delete('/admin/pdf/{pdf}', [AdminPdfController::class, 'delete'])->name('admin.pdf.delete');
Route::get('/pdfs/{pdf}', [StudentController::class, 'download'])->middleware('auth')->name('pdfs.download');
Route::get('/pdfs/{pdf}/view', [StudentController::class, 'view'])->middleware('auth')->name('pdfs.view');
Route::get('/pdfs/message/{pdfPath}', [StudentController::class, 'downloadMessagePdf'])->middleware('auth')->name('pdfs.downloadMessagePdf');

Route::get('/admin/teachers', [AdminUserController::class, 'teachers'])->name('admin.teachers');
Route::get('/admin/students', [AdminUserController::class, 'students'])->name('admin.students');
Route::get('/admin/works', [AdminPdfController::class, 'index'])->name('admin.works');

Route::put('/admin/teachers/{teacher}', [AdminUserController::class, 'updateTeacher'])->name('admin.teachers.update');
Route::delete('/admin/teachers/{teacher}', [AdminUserController::class, 'deleteTeacher'])->name('admin.teachers.delete');
Route::post('/admin/teachers', [AdminUserController::class, 'createTeacher'])->name('admin.teachers.create');

Route::put('/admin/students/{student}', [AdminUserController::class, 'updateStudent'])->name('admin.students.update');
Route::delete('/admin/students/{student}', [AdminUserController::class, 'deleteStudent'])->name('admin.students.delete');
Route::post('/admin/students', [AdminUserController::class, 'createStudent'])->name('admin.students.create');

Route::get('/student/messages', [StudentMessageController::class, 'index'])->name('student.messages');
Route::post('/student/messages', [StudentMessageController::class, 'store'])->name('student.messages.send');

Route::get('/teacher/messages', [TeacherMessageController::class, 'index'])->name('teacher.messages');
Route::post('/teacher/messages', [TeacherMessageController::class, 'store'])->name('teacher.messages.send');
Route::delete('/teacher/pdfs/{pdf}', [TeacherPdfController::class, 'destroy'])->name('teacher.pdfs.delete');

Route::middleware(['auth'])->group(function () {
    // ...
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

require __DIR__.'/auth.php';
