<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiacao extends Model
{
    use HasFactory;
    protected $fillable = [
        'longitude',
        'latitude',
        '00_annual',
        '01_jan',
        '02_feb',
        '03_mar',
        '04_apr',
        '05_may',
        '06_jun',
        '07_jul',
        '08_aug',
        '09_sep',
        '10_oct',
        '11_nov',
        '12_dez',
       /*  'longitude2',
        'latitude2',
        'd_longitude',
        'd_latitude',
        'A',
        'C',
        'D' */
    ];
}
