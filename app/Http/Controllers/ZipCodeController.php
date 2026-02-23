<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ZipExportMail;

class ZipCodeController extends Controller
{
    // 1. Listázás és Keresés
    public function index(Request $request)
    {
        $query = ZipCode::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zip_code', 'like', '%' . $searchTerm . '%');
        }

        // 20 elem oldalanként, megtartva a keresési paramétert a lapozásnál
        $zipCodes = $query->paginate(20)->withQueryString();

        return view('zipcodes.index', compact('zipCodes'));
    }

    // 2. Adatmódosítás (Edit űrlap) - Csak hitelesített felhasználóknak
    public function edit(ZipCode $zipCode)
    {
        return view('zipcodes.edit', compact('zipCode'));
    }

    // 3. Adatmódosítás mentése (Update) - Csak hitelesített felhasználóknak
    public function update(Request $request, ZipCode $zipCode)
    {
        $request->validate([
            'zip_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
        ]);

        $zipCode->update($request->only(['zip_code', 'city']));

        return redirect()->route('zipcodes.index')->with('success', 'Adat sikeresen módosítva!');
    }

    // 4. CSV Export
    public function exportCsv(Request $request)
    {
        $zipCodes = ZipCode::all();
        $filename = "iranyitoszamok.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Iranyitoszam', 'Telepules']); // Fejléc

        foreach($zipCodes as $row) {
            fputcsv($handle, [$row->id, $row->zip_code, $row->city]);
        }
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    // 5. PDF Export
    public function exportPdf()
    {
        // Limitáljuk 200-ra, hogy a PDF generáló ne fagyjon ki a 3400 adattól
        $zipCodes = ZipCode::limit(200)->get(); 
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));
        
        return $pdf->download('iranyitoszamok.pdf');
    }

    // 6. E-mail küldés PDF csatolmánnyal
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $zipCodes = ZipCode::limit(200)->get();
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));

        // E-mail küldése a MailHog felé
        Mail::to($request->email)->send(new ZipExportMail($pdf->output()));

        return back()->with('success', 'E-mail sikeresen elküldve a PDF csatolmánnyal!');
    }
}