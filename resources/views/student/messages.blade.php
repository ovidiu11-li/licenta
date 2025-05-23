@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('student.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">school</span>
                        Licență/Disertație
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.messages') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                        <span class="material-icons mr-3">chat</span>
                        Mesagerie
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.feedback') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('student.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">grading</span>
                        Feedback
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-2xl mx-auto mt-10 p-6">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-2 flex items-center"><span class="material-icons mr-2">chat</span>Mesagerie cu profesorul</h2>
            @if($teacher)
                <div class="mb-4 text-gray-700">
                    <span class="font-semibold">Profesor:</span> {{ $teacher->name }} ({{ $teacher->email }})
                </div>
                <div class="space-y-4 mb-6 max-h-[400px] overflow-y-auto">
                    @forelse($messages as $msg)
                        <div class="flex flex-col {{ $msg->sender_id == $student->id ? 'items-end' : 'items-start' }}">
                            <div class="px-4 py-2 rounded-lg {{ $msg->sender_id == $student->id ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-800' }} max-w-[80%] break-words break-all overflow-wrap-anywhere">
                                <div class="text-xs font-semibold mb-1">{{ $msg->sender->name }} <span class="text-gray-400">{{ $msg->created_at->format('d.m.Y H:i') }}</span></div>
                                <div class="break-words break-all overflow-wrap-anywhere">{{ $msg->content }}</div>
                                @if($msg->pdf_path)
                                    <div class="mt-2">
                                        <a href="{{ route('pdfs.downloadMessagePdf', ['pdfPath' => basename($msg->pdf_path)]) }}" class="text-blue-600 hover:underline" target="_blank">Descarcă PDF</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-400 text-center">Nu există mesaje încă.</div>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('student.messages.send') }}" enctype="multipart/form-data" class="flex flex-col gap-3">
                    @csrf
                    <textarea name="content" class="border rounded p-2 w-full" rows="3" placeholder="Scrie un mesaj..." required></textarea>
                    <input type="file" name="pdf" accept=".pdf" class="block" />
                    <button type="submit" class="self-end bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Trimite</button>
                </form>
            @else
                <div class="text-gray-500">Nu ai profesor asociat. Contactează administratorul.</div>
            @endif
        </div>
    </main>
</div>
@endsection 