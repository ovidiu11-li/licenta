<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white">

        <!-- HEADER -->


            <header class="bg-white border-b border-black-200 px-6 py-10 relative flex items-center justify-center">
    <!-- text centrat absolut -->
    <h1 class="text-xl font-semibold text-gray-800 absolute left-1/2 transform -translate-x-1/2">
        Platformă Licență
    </h1>

    <!-- iconita de user dreapta -->
    <div class="absolute right-6 flex items-center gap-4">
        @auth
        <div x-data="{ open: false }" class="relative">
            <button id="avatarButton"
                @click="open = !open"
                class="w-10 h-10 bg-yellow-300 rounded-full flex items-center justify-center font-bold text-sm text-gray-800 shadow hover:scale-105 transition focus:outline-none">
                @php
                    $name = Auth::user()->name;
                    $firstInitial = mb_substr($name, 0, 1);
                    $spacePos = mb_strpos($name, ' ');
                    $secondInitial = $spacePos !== false ? mb_substr($name, $spacePos + 1, 1) : '';
                    echo mb_strtoupper($firstInitial . $secondInitial);
                @endphp
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 z-50" style="display: none;" x-transition>
                <div class="p-4 border-b border-gray-100">
                    <div class="font-semibold text-gray-800 text-base">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="p-2">
                    @csrf
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.welcome') : (Auth::user()->role === 'teacher' ? route('teacher.welcome') : route('student.welcome')) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-medium mb-1">
                        Welcome
                    </a>
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium">
                        Ieșire din cont
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</header>

   


            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
