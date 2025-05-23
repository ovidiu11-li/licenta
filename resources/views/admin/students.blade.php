@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.teachers') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="material-icons mr-3">groups</span>
                        Administrare profesori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.students') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                        <span class="material-icons mr-3">school</span>
                        Administrare studenți
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.works') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="material-icons mr-3">description</span>
                        Administrare lucrări
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-6xl mx-auto mt-10 p-6">
        <div class="bg-white p-8 rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">Administrare studenți</h1>
            <form method="POST" action="{{ route('admin.students.create') }}" class="mb-8 flex gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Nume</label>
                    <input type="text" name="name" class="border rounded px-2 py-1 text-sm w-full" required />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Email</label>
                    <input type="email" name="email" class="border rounded px-2 py-1 text-sm w-full" required />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Parolă</label>
                    <input type="password" name="password" class="border rounded px-2 py-1 text-sm w-full" required />
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Creează student</button>
            </form>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nume</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Profesor</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50">
                        <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="contents">
                            @csrf
                            @method('PUT')
                            <td class="px-4 py-2">
                                <input type="text" name="name" value="{{ $student->name }}" class="border rounded px-2 py-1 text-sm w-full" />
                            </td>
                            <td class="px-4 py-2">
                                <input type="email" name="email" value="{{ $student->email }}" class="border rounded px-2 py-1 text-sm w-full" />
                            </td>
                            <td class="px-4 py-2">
                                <select name="teacher_id" class="border rounded px-2 py-1 text-sm w-full">
                                    <option value="">-- Fără profesor --</option>
                                    @foreach($teachers as $teacherOption)
                                        <option value="{{ $teacherOption->id }}" {{ $student->teacher_id == $teacherOption->id ? 'selected' : '' }}>{{ $teacherOption->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Salvează</button>
                        </form>
                        <form method="POST" action="{{ route('admin.students.delete', $student->id) }}" onsubmit="return confirm('Sigur vrei să ștergi acest student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Șterge</button>
                        </form>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($students->count() === 0)
                <div class="text-gray-400 text-center py-8">Niciun student găsit.</div>
            @endif
        </div>
    </main>
</div>
@endsection 