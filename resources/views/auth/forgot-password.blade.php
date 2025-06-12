@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="flex flex-col items-center w-full px-4">

        {{-- Card --}}
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">

            {{-- Title --}}
            <h1 class="text-2xl font-bold mb-4 text-left">Resetare parolă</h1>

            {{-- Info box --}}
            <br>
            <div class="mb-4 text-sm text-blue-800 bg-blue-100 p-4 rounded flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <span>Introdu adresa de e-mail asociată contului pentru a primi instrucțiunile ulterioare</span>
            </div>
            <br>

            {{-- Status message --}}
            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-100 p-4 rounded">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input id="email" type="email" name="email" required autofocus
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                           placeholder="E-mail">
                    <span class="text-red-500 text-sm mt-1 hidden" id="email-error">Acest câmp este obligatoriu</span>
                </div>

                {{-- Submit --}}
                <div class="mb-4">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Solicită resetare
                    </button>
                </div>

                {{-- Link to login --}}
                <div class="text-left text-sm">
                    Ai deja cont?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Autentificare</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
