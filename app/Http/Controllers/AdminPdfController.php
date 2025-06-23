<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPdfController extends Controller
{
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
        
        // gruparea pdf-urilor dupa root pdf pentru a arata versiunile impreuna
        $groupedPdfs = $pdfs->groupBy(function($pdf) {
            return $pdf->getRootPdf()->id;
        });
        
        return view('admin.works', compact('groupedPdfs'));
    }
} 