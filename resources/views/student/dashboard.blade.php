@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                        <span class="material-icons mr-3">school</span>
                        Licență/Disertație
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.messages') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('student.messages') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">chat</span>
                        Mesagerie
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.feedback') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('student.feedback') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">grading</span>
                        Feedback
                    </a>
                </li>
            </ul>
        </nav>
        <div class="mt-10">
            <a href="#" class="flex items-center px-3 py-2 text-gray-500 hover:text-red-600">
                <span class="material-icons mr-3">logout</span>
                Ieșire din cont
            </a>
        </div>
    </aside>
    <!-- body -->
    <main class="flex-1 max-w-6xl mx-auto mt-10 p-6">
        <div x-data="pdfUploader()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- uploadare pdf -->
            <div class="bg-white p-6 rounded-xl shadow flex flex-col gap-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Încarcă lucrarea ta (PDF)</h2>
                <input type="file" accept=".pdf" class="block w-full border border-gray-300 rounded p-2" @change="previewPDF($event)" x-ref="fileInput" />
                <div class="flex gap-2 mt-2" x-show="pdfUrl">
                    <button @click="uploadPDF" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">Încarcă PDF</button>
                    <button @click="removePDF" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded shadow">Șterge</button>
                </div>
                <form x-ref="uploadForm" method="POST" action="{{ route('student.upload') }}" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="pdf" x-ref="hiddenFileInput" />
                </form>
            </div>

            <!-- Versiuni PDF -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Versiunile lucrării tale</h2>
                
                @php
                    $student = Auth::user();
                    $pdfs = \App\Models\Pdf::where('user_id', $student->id)->orderByDesc('version')->orderByDesc('created_at')->get();
                    $currentPdf = $currentPdf ?? null;
                @endphp

                @if($pdfs->count() > 0)
                    <div class="space-y-4">
                        @foreach($pdfs as $pdf)
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
                                                @php
                                                    $feedback = $pdf->feedbacks->where('student_id', Auth::id())->first();
                                                @endphp
                                                @if($feedback)
                                                    <span class="ml-4 font-bold text-green-700">Notă: {{ $feedback->grade }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('pdfs.view', $pdf->id) }}" target="_blank" 
                                           class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs">
                                            Vezi
                                        </a>
                                        <a href="{{ route('pdfs.download', $pdf->id) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            Descarcă
                                        </a>
                                        @if($pdfs->count() > 1)
                                            <form method="POST" action="{{ route('student.pdf.delete', $pdf->id) }}" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Sigur vrei să ștergi această versiune?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    Șterge
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-icons text-4xl mb-2">cloud_upload</span>
                        <p>Nu ai încărcat nicio lucrare încă.</p>
                        <p class="text-sm">Încarcă prima ta lucrare folosind formularul din stânga.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Previzualizare PDF -->
        @if($currentPdf)
        <div class="mt-6 bg-gradient-to-br from-gray-50 to-white p-0 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-100 rounded-t-2xl border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700">Previzualizare lucrare curentă</h2>
                <span class="material-icons text-gray-400">picture_as_pdf</span>
            </div>
            <div class="p-6">
                <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-white">
                    <iframe src="{{ route('pdfs.view', $currentPdf->id) }}" class="w-full h-[600px] border-0" style="background: #f8fafc;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
        @endif
    </main>
</div>

<script>
function pdfUploader() {
    return {
        pdfUrl: null,
        file: null,
        previewPDF(event) {
            const file = event.target.files[0];
            if (file && file.type === 'application/pdf') {
                this.file = file;
                this.pdfUrl = URL.createObjectURL(file);
                // sincronizare file cu formularul ascuns pentru upload
                this.$refs.hiddenFileInput.files = event.target.files;
            } else {
                this.removePDF();
            }
        },
        removePDF() {
            this.pdfUrl = null;
            this.file = null;
            this.$refs.fileInput.value = '';
            this.$refs.hiddenFileInput.value = '';
        },
        uploadPDF() {
            if (this.file) {
                this.$refs.uploadForm.submit();
            }
        }
    }
}
</script>
@endsection