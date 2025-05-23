<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pdf;
use Illuminate\Support\Facades\Storage;

class TeacherPdfController extends Controller
{
    public function destroy($id)
    {
        $pdf = Pdf::findOrFail($id);
        // Delete the file from storage
        Storage::disk('private')->delete($pdf->stored_name);
        // Delete the database record
        $pdf->delete();
        return redirect()->back()->with('success', 'PDF șters cu succes.');
    }
} 