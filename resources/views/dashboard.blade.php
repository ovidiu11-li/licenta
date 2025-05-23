@extends('layouts.app')

@section('content')
<main class="max-w-6xl mx-auto mt-10 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

        <!-- Upload form -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Încarcă lucrarea ta (PDF)</h2>
            <form class="space-y-4">
                <input type="file" accept=".pdf" class="block w-full border border-gray-300 rounded p-2" />
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                    Încarcă PDF
                </button>
            </form>
        </div>

        <!-- PDF Preview -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Previzualizare lucrare</h2>
            <iframe src="https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf"
                class="w-full h-[700px] border rounded" frameborder="0"></iframe>
        </div>

    </div>
</main>
@endsection