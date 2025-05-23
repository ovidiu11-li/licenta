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
                    <a href="{{ route('admin.students') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        <span class="material-icons mr-3">school</span>
                        Administrare studenți
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.works') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
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
            <h1 class="text-2xl font-bold mb-6">Administrare lucrări</h1>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nume lucrare</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Profesor</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data încărcării</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pdfs as $pdf)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $pdf->original_name }}</td>
                        <td class="px-4 py-2">{{ $pdf->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $pdf->user && $pdf->user->teacher ? $pdf->user->teacher->name : '-' }}</td>
                        <td class="px-4 py-2">{{ $pdf->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('pdfs.view', $pdf->id) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs mr-2">Vezi</a>
                            <a href="{{ route('pdfs.download', $pdf->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs mr-2">Descarcă</a>
                            <form method="POST" action="{{ route('admin.pdf.delete', $pdf->id) }}" class="inline" onsubmit="return confirm('Sigur vrei să ștergi această lucrare?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Șterge</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($pdfs->count() === 0)
                <div class="text-gray-400 text-center py-8">Nicio lucrare găsită.</div>
            @endif
        </div>
    </main>
</div>
@endsection 