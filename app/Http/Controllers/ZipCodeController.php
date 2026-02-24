<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ZipExportMail;

class ZipCodeController extends Controller
{
    // Közös függvény, ami visszaadja a szűrt adatokat a listázásnak és az exportoknak is!
    private function getFilteredQuery(Request $request)
    {
        $query = ZipCode::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('zip_code', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('county')) {
            $query->where('county', $request->county);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        
        // Kinyerjük a megyéket a legördülő menühöz (ABC sorrendben)
        $counties = ZipCode::whereNotNull('county')->distinct()->orderBy('county')->pluck('county');

        $zipCodes = $query->paginate(20)->withQueryString();

        return view('zipcodes.index', compact('zipCodes', 'counties'));
    }

    public function edit(ZipCode $zipCode)
    {
        return view('zipcodes.edit', compact('zipCode'));
    }

    public function update(Request $request, ZipCode $zipCode)
    {
        $request->validate(['zip_code' => 'required', 'city' => 'required']);
        $zipCode->update($request->only(['zip_code', 'city']));
        return redirect()->route('zipcodes.index')->with('success', 'Adat sikeresen módosítva!');
    }

    public function exportCsv(Request $request)
    {
        $zipCodes = $this->getFilteredQuery($request)->get(); // Csak a szűrt adatok!
        
        $filename = "iranyitoszamok.csv";
        $handle = fopen($filename, 'w+');
        // Kódlap beállítása a magyar ékezetek miatt (BOM)
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['ID', 'Irányítószám', 'Település', 'Megye']);

        foreach($zipCodes as $row) {
            fputcsv($handle, [$row->id, $row->zip_code, $row->city, $row->county]);
        }
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $zipCodes = $this->getFilteredQuery($request)->limit(500)->get(); // Csak a szűrt adatok!
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));
        return $pdf->download('iranyitoszamok_szurt.pdf');
    }

    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $zipCodes = $this->getFilteredQuery($request)->limit(500)->get(); // Csak a szűrt adatok!
        $pdf = Pdf::loadView('zipcodes.pdf', compact('zipCodes'));

        Mail::to($request->email)->send(new ZipExportMail($pdf->output()));

        return back()->with('success', 'A szűrt lista elküldve a megadott e-mail címre!');
    }
}