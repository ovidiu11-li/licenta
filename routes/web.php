<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminPdfController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StudentMessageController;
use App\Http\Controllers\TeacherMessageController;
use App\Http\Controllers\TeacherPdfController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/welcome');
            case 'teacher':
                return redirect('/teacher/welcome');
            case 'student':
            default:
                return redirect('/student/welcome');
        }
    }
    return view('auth.login');
});

// rutele adminului
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/welcome', [AdminController::class, 'welcome'])->name('admin.welcome');
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::get('/teachers', [AdminUserController::class, 'teachers'])->name('admin.teachers');
        Route::get('/students', [AdminUserController::class, 'students'])->name('admin.students');
        Route::get('/works', [AdminPdfController::class, 'index'])->name('admin.works');
        
        Route::put('/teachers/{teacher}', [AdminUserController::class, 'updateTeacher'])->name('admin.teachers.update');
        Route::delete('/teachers/{teacher}', [AdminUserController::class, 'deleteTeacher'])->name('admin.teachers.delete');
        Route::post('/teachers', [AdminUserController::class, 'createTeacher'])->name('admin.teachers.create');
        
        Route::put('/students/{student}', [AdminUserController::class, 'updateStudent'])->name('admin.students.update');
        Route::delete('/students/{student}', [AdminUserController::class, 'deleteStudent'])->name('admin.students.delete');
        Route::post('/students', [AdminUserController::class, 'createStudent'])->name('admin.students.create');
        
        Route::delete('/pdf/{pdf}', [AdminPdfController::class, 'delete'])->name('admin.pdf.delete');
    });

    // rutele profesorului
    Route::prefix('teacher')->group(function () {
        Route::get('/welcome', [TeacherController::class, 'welcome'])->name('teacher.welcome');
        Route::get('/dashboard', [StudentController::class, 'teacherDashboard'])->name('teacher.dashboard');
        Route::get('/messages', [TeacherMessageController::class, 'index'])->name('teacher.messages');
        Route::post('/messages', [TeacherMessageController::class, 'store'])->name('teacher.messages.send');
        Route::get('/feedback/{pdf}', [StudentController::class, 'showFeedbackForm'])->name('teacher.feedback.form');
        Route::post('/feedback/{pdf}', [StudentController::class, 'storeFeedback'])->name('teacher.feedback.store');
        Route::get('/feedbacks', [StudentController::class, 'teacherFeedbacksPage'])->name('teacher.feedbacks');
        Route::delete('/pdfs/{pdf}', [TeacherPdfController::class, 'destroy'])->name('teacher.pdfs.delete');
    });

    // rutele studentului
    Route::prefix('student')->group(function () {
        Route::get('/welcome', [StudentController::class, 'welcome'])->name('student.welcome');
        Route::get('/dashboard', [StudentController::class, 'studentDashboard'])->name('student.dashboard');
        Route::get('/messages', [StudentMessageController::class, 'index'])->name('student.messages');
        Route::post('/messages', [StudentMessageController::class, 'store'])->name('student.messages.send');
        Route::get('/feedback', [StudentController::class, 'feedbackPage'])->name('student.feedback');
        Route::post('/upload', [StudentController::class, 'uploadPdf'])->name('student.upload');
        Route::delete('/pdf/{pdf}', [StudentController::class, 'deletePdfVersion'])->name('student.pdf.delete');
    });

    // Shared PDF routes
    Route::get('/pdfs/message/{pdfPath}', [StudentController::class, 'downloadMessagePdf'])->name('pdfs.downloadMessagePdf');
    Route::get('/pdfs/token/{token}', [StudentController::class, 'downloadByToken'])->name('pdfs.download.token');
    Route::get('/pdfs/token/{token}/view', [StudentController::class, 'viewByToken'])->name('pdfs.view.token');
});

require __DIR__.'/auth.php';
