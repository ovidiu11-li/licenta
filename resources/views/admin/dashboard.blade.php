@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4 min-h-screen">
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.teachers') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.teachers') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">groups</span>
                        Administrare profesori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.students') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.students') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                        <span class="material-icons mr-3">school</span>
                        Administrare studenți
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.works') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.works') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
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
            <h1 class="text-2xl font-bold mb-4">Panoul de administrare.</h1>
        </div>
    </main>
</div>
@endsection
