<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pdf;

class AdminController extends Controller
{
    public function welcome()
    {
        // Numărul total de profesori
        $teachersCount = User::where('role', 'teacher')->count();
        
        // Numărul total de studenți
        $studentsCount = User::where('role', 'student')->count();
        
        // Numărul total de lucrări
        $pdfsCount = Pdf::count();
        
        // Numărul de studenți fără profesor asignat
        $unassignedStudentsCount = User::where('role', 'student')
            ->whereNull('teacher_id')
            ->count();
            
        return view('admin.welcome', compact('teachersCount', 'studentsCount', 'pdfsCount', 'unassignedStudentsCount'));
    }
} 