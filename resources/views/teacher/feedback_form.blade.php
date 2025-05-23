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
            </ul>
        </nav>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">Feedback pentru lucrarea studentului</h2>
        <div class="mb-4">
            <div><span class="font-semibold">Student:</span> {{ $student->name }} ({{ $student->email }})</div>
            <div><span class="font-semibold">Lucrare:</span> {{ $pdf->original_name }}</div>
        </div>
        @php
            $feedback = \App\Models\Feedback::where('pdf_id', $pdf->id)->where('teacher_id', auth()->id())->first();
        @endphp
        <form method="POST" action="{{ route('teacher.feedback.store', $pdf->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Notă (1-10):</label>
                <input type="number" name="grade" min="1" max="10" class="border rounded p-2 w-24" value="{{ old('grade', $feedback?->grade) }}" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Mesaj feedback:</label>
                <textarea name="message" class="border rounded p-2 w-full" rows="4" required>{{ old('message', $feedback?->message) }}</textarea>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Salvează feedback</button>
            <a href="{{ route('teacher.dashboard') }}" class="ml-4 text-gray-500 hover:underline">Renunță</a>
        </form>
    </main>
</div>
@endsection 