@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">description</span>
                        Lucrări studenți
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.messages') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('teacher.messages') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">chat</span>
                        Mesagerie
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.feedbacks') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                        <span class="material-icons mr-3">grading</span>
                        Feedbackuri acordate
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-3xl mx-auto mt-10 p-6">
        <h1 class="text-2xl font-bold mb-6">Feedbackuri acordate</h1>
        <div class="bg-white rounded-xl shadow p-6">
            @if($feedbacks->count() === 0)
                <div class="text-gray-400 text-center py-8">Nu ai acordat încă feedback niciunei lucrări.</div>
            @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lucrare</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notă</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Feedback</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $fb)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $fb->pdf->original_name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $fb->student->name ?? '-' }}</td>
                        <td class="px-4 py-2 font-bold text-lg text-center">{{ $fb->grade }}</td>
                        <td class="px-4 py-2">{{ $fb->message }}</td>
                        <td class="px-4 py-2 text-xs text-gray-500">{{ $fb->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </main>
</div>
@endsection 