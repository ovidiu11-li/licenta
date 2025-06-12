@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="material-icons mr-3">description</span>
                        Lucrări studenți
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.messages') }}" class="flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('teacher.messages') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="material-icons mr-3">chat</span>
                        Mesagerie
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher.feedbacks') }}" class="flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('teacher.feedbacks') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="material-icons mr-3">grading</span>
                        Feedbackuri acordate
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-6xl mx-auto mt-10 p-6">
        <div class="bg-white p-8 rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">Lucrări studenți</h1>
            
            @if($groupedPdfs->count() > 0)
                <div class="space-y-6">
                    @foreach($groupedPdfs as $rootPdfId => $pdfs)
                        @php
                            $rootPdf = $pdfs->first()->getRootPdf();
                            $student = $rootPdf->user;
                            $allVersions = $rootPdf->allVersions();
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $student->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $allVersions->count() }} versiuni
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($allVersions as $pdf)
                                    <div class="border border-gray-200 rounded-lg p-4 {{ $pdf->is_current ? 'bg-blue-50 border-blue-200' : 'bg-gray-50' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="material-icons text-red-500">picture_as_pdf</span>
                                                <div>
                                                    <div class="font-semibold text-gray-800">
                                                        {{ $pdf->original_name }}
                                                        @if($pdf->is_current)
                                                            <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Versiunea curentă</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        Versiunea {{ $pdf->version }} • Încărcat {{ $pdf->created_at->format('d.m.Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('pdfs.view.token', $pdf->token) }}" target="_blank" 
                                                   class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs">
                                                    Vezi
                                                </a>
                                                <a href="{{ route('pdfs.download.token', $pdf->token) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                    Descarcă
                                                </a>
                                                @php
                                                    $feedback = \App\Models\Feedback::where('pdf_id', $pdf->id)->where('teacher_id', auth()->id())->first();
                                                @endphp
                                                <a href="{{ route('teacher.feedback.form', $pdf->id) }}" 
                                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                    {{ $feedback ? 'Editare feedback' : 'Acordă feedback' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-400 text-center py-8">
                    <span class="material-icons text-4xl mb-2">folder_open</span>
                    <p>Nicio lucrare încărcată de studenții tăi.</p>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
