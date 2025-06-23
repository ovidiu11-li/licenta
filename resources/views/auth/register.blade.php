@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="flex flex-col items-center w-full px-4">
        {{-- Card --}}
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">

            {{-- Title --}}
            <h1 class="text-2xl font-bold mb-4 text-left">Înregistrare în sistem</h1>

            {{-- Info Box --}}
            <div class="mb-4 text-sm text-blue-800 bg-blue-100 p-4 rounded flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <span>
                    Înregistrarea este permisă doar pentru studenții UAB cu adrese de e-mail de forma: <strong>nume.complet.infoXX@uab.ro</strong> (unde XX este anul școlar în care sunt înscriși)
                </span>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nume complet</label>
                    <input id="name" type="text" name="name" required
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm @error('name') border-red-500 @enderror"
                        placeholder="Nume complet"
                        value="{{ old('name') }}">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail UAB</label>
                    <input id="email" type="email" name="email" required
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm @error('email') border-red-500 @enderror"
                        placeholder="nume.complet.infoXX@uab.ro"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Parolă</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm @error('password') border-red-500 @enderror"
                           placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmare parolă</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                           placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                </div>

                {{-- Submit --}}
                <div class="mb-4">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Înregistrare
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
