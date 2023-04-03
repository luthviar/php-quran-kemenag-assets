<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GeneratingUrlController extends Controller
{
    public function runUrlToImageFile() {
        set_time_limit(0);

        $lastSurah = 114;
        $firstSurah = 1;
        
        //expected result example: https://media.qurankemenag.net/khat2/QK_001.webp

        $baseUrl = "https://media.qurankemenag.net/khat2/";
        for ($i=$firstSurah; $i <= $lastSurah; $i++) {
            $numberPath = "";            
            
            if ($i<10) {
                $numberPath = "00".$i;
            } else if ($i<100) {
                $numberPath = "0".$i;
            } else if ($i>=100) {
                $numberPath = $i;
            }
            $fileName = "QK_" . $numberPath . ".webp";
            $urlToGet = $baseUrl . $fileName;
                        
            $response = Http::get($urlToGet);
            if ($response->ok()) {
                $image = $response->body();
                Storage::disk('local')->put($fileName, $image);                
            } else {
                
            }
            
            sleep(3);
        }        

        dd("success to save image file");

    }

    public function runUrlToJsonFile() {
        set_time_limit(0);

        $lastSurah = 114;
        $firstSurah = 1;
        
        //expected result example: https://web-api.qurankemenag.net/quran-ayah?start=0&limit=286&surah=14
        
        for ($i=$firstSurah; $i <= $lastSurah; $i++) {                   
            
            $response = Http::get('https://web-api.qurankemenag.net/quran-ayah', [
                'start' => 0,
                'limit' => 286,
                'surah' => $i
            ]);
            $fileName = "quran-surah-". $i .".json";
            if ($response->ok()) {
                $data = $response->json();
                $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                Storage::disk('local')->put($fileName, $json);
            }

            sleep(3);
        }        

        dd("success convert to json file");       
    }

    public function convertUnicodeToArabic() {     
        set_time_limit(0);

        $lastSurah = 114;
        $firstSurah = 1;
                
        for ($i=$firstSurah; $i <= $lastSurah; $i++) {   
            $path = storage_path("app/quran-surah-1.json");
            $contents = file_get_contents($path);
            $data = json_decode($contents, true);
            $surahData = $data['data'];
            $arabic = $surahData[0]['arabic'];
        }
        dd("success converted unicode");
        $path = storage_path('app/quran-surah-1.json');
        $contents = file_get_contents($path);
        $data = json_decode($contents, true);
        $surahData = $data['data'];
        $arabic = $surahData[0]['arabic'];
        dd($arabic);
        $unicodeString = "\u0628\u0650\u0633\u0652\u0645\u0650 \u0627\u0644\u0644\u0651\u0670\u0647\u0650 \u0627\u0644\u0631\u0651\u064e\u062d\u0652\u0645\u0670\u0646\u0650 \u0627\u0644\u0631\u0651\u064e\u062d\u0650\u064a\u0652\u0645\u0650";
        $arabicString = json_decode('["'.$unicodeString.'"]')[0];
        dd($arabicString); // Output: بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
    }
}
