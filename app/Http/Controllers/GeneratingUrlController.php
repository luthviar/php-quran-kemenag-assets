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
                $json = json_encode($data, JSON_PRETTY_PRINT);
                Storage::disk('local')->put($fileName, $json);
            }

            sleep(3);
        }        

        dd("success convert to json file");       
    }
}
