@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Bun venit, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Panoul de control pentru profesori
            </p>
        </div>

        <div class="space-y-12">
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Studenți Asociați</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->students()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Lucrări de Evaluat</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Pdf::whereHas('user', function($query) { $query->where('teacher_id', Auth::id()); })->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm p-8 hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('teacher.dashboard') }}" class="block">
                        <div class="text-center">
                            <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Lucrări studenți</h3>
                            <p class="text-base text-gray-600">Vizualizează studenții și lucrările lor</p>
                        </div>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-8 hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('teacher.messages') }}" class="block">
                        <div class="text-center">
                            <div class="mx-auto w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Mesagerie</h3>
                            <p class="text-base text-gray-600">Comunică cu unul din studenții tăi</p>
                        </div>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-8 hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('teacher.feedbacks') }}" class="block">
                        <div class="text-center">
                            <div class="mx-auto w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Feedback</h3>
                            <p class="text-base text-gray-600">Vizualizează feedback-urile acordate de tine</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection