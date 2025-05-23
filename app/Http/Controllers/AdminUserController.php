<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AdminUserController extends Controller
{
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.teachers', compact('teachers'));
    }

    public function updateTeacher(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $teacher->id,
        ]);
        $teacher->update($request->only('name', 'email'));
        return redirect()->back()->with('success', 'Profesorul a fost actualizat.');
    }

    public function deleteTeacher(User $teacher)
    {
        $teacher->delete();
        return redirect()->back()->with('success', 'Profesorul a fost șters.');
    }

    public function createTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'teacher',
        ]);
        return redirect()->back()->with('success', 'Profesorul a fost creat cu succes.');
    }

    public function students()
    {
        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.students', compact('students', 'teachers'));
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->id,
            'teacher_id' => 'nullable|exists:users,id',
        ]);
        $student->update($request->only('name', 'email', 'teacher_id'));
        return redirect()->back()->with('success', 'Studentul a fost actualizat.');
    }

    public function deleteStudent(User $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Studentul a fost șters.');
    }

    public function createStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student',
        ]);
        return redirect()->back()->with('success', 'Studentul a fost creat cu succes.');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
} 