@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
        <div class="text-left mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Bine ai revenit</h2>
        </div>

        <div class="flex justify-center gap-4 mb-4">
            <a href="#" class="flex items-center justify-center border rounded px-4 py-2 w-full hover:bg-gray-100">
                <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5 mr-2">
                Google
            </a>
            <a href="#" class="flex items-center justify-center border rounded px-4 py-2 w-full hover:bg-gray-100">
                <img src="https://www.svgrepo.com/download/452062/microsoft.svg" alt="Microsoft" class="w-5 h-5 mr-2">
                Microsoft
            </a>
        </div>

        <div class="flex items-center justify-center my-4">
            <hr class="w-full border-gray-300">
            <span class="px-3 text-gray-500 text-sm">sau</span>
            <hr class="w-full border-gray-300">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Utilizator sau e-mail</label>
                <input id="email" type="email" name="email" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                       placeholder="Utilizator sau e-mail">
                <span class="text-red-500 text-sm mt-1 hidden" id="email-error">Acest câmp este obligatoriu</span>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Parolă</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm"
                       placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                <span class="text-red-500 text-sm mt-1 hidden" id="password-error">Acest câmp este obligatoriu</span>
            </div>

            <div class="mb-4 text-left">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Ai uitat parola?</a>
            </div>

            <div class="mb-4">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Autentificare
                </button>
            </div>

            <div class="text-left text-sm">
                Nu ai cont? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Înregistrare</a>
            </div>
            </div>
        </form>
    </div>
</div>
@endsection
