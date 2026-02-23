<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZipCode;
use Illuminate\Support\Facades\Http;

class ZipCodeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Adatok lekérése a localhost:8000 API-ból...');

        // Itt adjuk meg a pontos URL-t, amit küldtél
        $response = Http::acceptJson()->get('http://localhost:8000/api/settlements');

        if ($response->successful()) {
            $adatok = $response->json();
            
            // Ha a Laravel API lapozást (pagination) használ, az adatok a 'data' kulcsban vannak
            $telepulesek = $adatok['data'] ?? $adatok;

            $count = 0;

            foreach ($telepulesek as $item) {
                // Biztosítjuk, hogy csak akkor mentsünk, ha van név és irányítószám
                if (isset($item['postal_code']) && isset($item['name'])) {
                    ZipCode::updateOrCreate(
                        // Keresési feltétel (ne duplikáljunk)
                        ['zip_code' => $item['postal_code']], 
                        // Mit mentsünk el a mi adatbázisunkba (a korábban megírt migráció alapján)
                        [
                            'zip_code' => $item['postal_code'], 
                            'city'     => $item['name']
                        ]
                    );
                    $count++;
                }
            }

            $this->command->info("Sikeresen áthúztunk {$count} települést a saját mm_ adatbázisodba!");
        } else {
            $this->command->error("Hiba történt az API lekérésekor! Státuszkód: " . $response->status());
        }
    }
}