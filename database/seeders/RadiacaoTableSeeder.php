<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Radiacao;

class RadiacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Radiacao::truncate();
        $csvFile = fopen(base_path("database/data/global_horizontal_means.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Radiacao::create([
                    "longitude" => str_replace(',', '.', $data['0']),
                    "latitude" => str_replace(',', '.', $data['1']),
                    "00_annual" => $data['2'],
                    "01_jan" => $data['3'],
                    "02_feb" => $data['4'],
                    "03_mar" => $data['5'],
                    "04_apr" => $data['6'],
                    "05_may" => $data['7'],
                    "06_jun" => $data['8'],
                    "07_jul" => $data['9'],
                    "08_aug" => $data['10'],
                    "09_sep" => $data['11'],
                    "10_oct" => $data['12'],
                    "11_nov" => $data['13'],
                    "12_dez" => $data['14'],
                    "longitude2" => deg2rad(str_replace(',', '.', $data['0'])),
                    "latitude2" => deg2rad(str_replace(',', '.', $data['1']))
                ]);    
            }
            $firstline = false;
        }
        fclose($csvFile);

    }
}
