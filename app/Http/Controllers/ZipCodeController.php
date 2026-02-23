<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZipCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ZipExportMail;

class ZipCodeController extends Controller
{
    // 1. Keresés és listázás
    public function index(Request $request)
    {
        $query = ZipCode::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zip_code', 'like', '%' . $searchTerm . '%');
        }

        $zipCodes = $query->paginate(20);

        return view('zipcodes.index', compact('zipCodes'));
    }

    // 2. CSV Export
    public function exportCsv()
    {
        $zipCodes = ZipCode::all();
        $filename = "iranyitoszamok.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Irányítószám', 'Település']); // Fejléc

        foreach($zipCodes as $row) {
            fputcsv($handle, [$row->id, $row->zip_code, $row->city]); // Cseréld a te oszlopneveidre
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    // 3. PDF Export
    public function exportPdf()
    {
        $zipCodes = ZipCode::limit(100)->get(); // Érdemes limitálni, hogy ne fagyjon le a PDF generáló
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));
        return $pdf->download('iranyitoszamok.pdf');
    }

    // 4. Email küldés PDF csatolmánnyal
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $zipCodes = ZipCode::limit(100)->get();
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));

        Mail::to($request->email)->send(new ZipExportMail($pdf->output()));

        return back()->with('success', 'Email sikeresen elküldve a PDF csatolmánnyal!');
    }
}