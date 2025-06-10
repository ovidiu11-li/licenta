<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPdfController extends Controller
{
    public function rename(Request $request, Pdf $pdf)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
        ]);
        $pdf->original_name = $request->input('new_name');
        $pdf->save();
        return redirect()->back()->with('success', 'Numele fișierului a fost actualizat.');
    }

    public function delete(Pdf $pdf)
    {
        // stergerea fisierului din storage
        Storage::disk('public')->delete($pdf->stored_name);
        $pdf->delete();
        return redirect()->back()->with('success', 'PDF șters cu succes.');
    }

    public function index()
    {
        $pdfs = \App\Models\Pdf::with(['user', 'versions'])->get();
        
        // Group PDFs by their root PDF to show versions together
        $groupedPdfs = $pdfs->groupBy(function($pdf) {
            return $pdf->getRootPdf()->id;
        });
        
        return view('admin.works', compact('groupedPdfs'));
    }
} 