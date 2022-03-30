<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  \App\Http\Requests\Ferramenta1Request;
use App\Models\Radiacao;

class MainController extends Controller
{
    public function ferramenta1_form()
    {
        return view('ferramenta1');
    }
    public function ferramenta1_action(Ferramenta1Request $request)
    {
        $validated = $request->validated();
        dd("Teste", $validated);

    }

    public function teste()
    {
        $latitude = 0.5166399;
        $longitude = -66.2440148;
        $inclinacao = 30;
        $orientacao = 60;
        
        $latitude = deg2rad($latitude);
        $longitude = deg2rad($longitude);
        $raio_da_terra = 6371; // km

        //$data = Radiacao::select('latitude2', 'longitude2')->limit(50)->get()->toArray();
        $data = Radiacao::select('latitude2', 'longitude2')->get()->toArray();

        //dd($data);

        $data = array_map(fn($valor): array => [
            'latitude2' => $valor['latitude2'],
            'longitude2' => $valor['longitude2'],                                  
            'dLat' => $valor['latitude2'] - $latitude, 
            'dLon' => $valor['longitude2'] - $longitude, 
            'A' => 0, 
            'C' => 0, 
            'D' => 0
        ], $data);

        $data = array_map(fn($valor): array => [
            'latitude2' => $valor['latitude2'],
            'longitude2' => $valor['longitude2'],                                   
            'dLat' => $valor['dLat'], 
            'dLon' => $valor['dLon'], 
            'A' => sin($valor['dLat']/2) * sin($valor['dLat']/2) + cos($latitude) * cos($valor['latitude2']) * sin($valor['dLon']/2) * sin($valor['dLon']/2), 
            'C' => 0, 
            'D' => 0
        ], $data);

        $data = array_map(fn($valor): array => [
            'latitude2' => $valor['latitude2'],
            'longitude2' => $valor['longitude2'],                                   
            'dLat' => $valor['dLat'], 
            'dLon' => $valor['dLon'], 
            'A' => $valor['A'], 
            'C' => 2 * atan2(sqrt($valor['A']),sqrt(1-$valor['A'])), 
            'D' => 0
        ], $data);
        
        $data = array_map(fn($valor): array => [
            'latitude2' => $valor['latitude2'],
            'longitude2' => $valor['longitude2'],                                   
            'dLat' => $valor['dLat'], 
            'dLon' => $valor['dLon'], 
            'A' => $valor['A'], 
            'C' => $valor['C'], 
            'D' => $raio_da_terra * $valor['C']
        ], $data);

        uasort ( $data , function ($a, $b) {
                if($a['D'] == $b['D']) {
                    return 0;
                }
                return ($a['D'] < $b['D']) ? -1 : 1;
            }
        );
        
        $proximo = array_key_first($data);
        //$proximo = array_search(array_pop($data));

        //dd($data[$proximo]);
        //dd($data);
        dd($proximo, $data[$proximo]);

        return 10;
    }

}
function ktkddiario($KT)
{
    $KD = 0;
    if(0.17 <= $KT && $KT <= 0.75){
        $KD = (1.188-2.272*$KT+9.473*($KT)^2-21.856*($KT)^3+14.648*($KT)^4);
    }
    if(0.75 < $KT && $KT <= 0.8){
        $KD = 0.632-0.54*$KT;
    }
    if( $KT > 0.8){
        $KD = 0.2;
    }
    if($KT < 0.17){
        $KD = 0.99;
    }
    return $KD;
}
function Costetasexp($Declinacao,$Latitude,$Betaa,$Azimute,$W)
{
    $Costetas = sin($Declinacao)*sin($Latitude)*cos($Betaa)-sin($Declinacao)*cos($Latitude)*sin($Betaa)*cos($Azimute)+cos($Declinacao)*cos($Latitude)*cos($Betaa)*cos($W)+ cos($Declinacao)*sin($Latitude)*sin($Betaa)*cos($Azimute)*cos($W)+cos($Declinacao)*sin($Betaa)*sin($Azimute)*sin($W);
    return $Costetas;
}
function Fatortransposicaoliu($Betaa)
{
    $Fr= (1 + cos($Betaa))/2;
    return $Fr;
}
function Ktalehorario($KT,$KTM,$LAMBDA)
{
    $Ktale = 0;
    if(0.10 <= $KT && $KT <= 0.75){
        $Ktale= $KTM+$LAMBDA*(0.25-2*($KT-0.4)^2);
    }
    if(0.10> $KT || $KT >0.75){
        $Ktale= $KTM;
    }
    if($KTM <=0){
        $Ktale = 0;
    }
}
function idalee($Ialecorrigido,$ktalee)
{
    $Idale = 0;
    if($ktalee <=0.8 && $ktalee >=0.22){
        $Idale= $Ialecorrigido*(0.9511-0.1604*$ktalee+4.388*$ktalee^2-16.638*$ktalee^3+12.336*$ktalee^4);
    }
    if($ktalee<0.22){
        $Idale=$Ialecorrigido*(1-0.09*$ktalee);
    }
    if($ktalee>0.8){
        $Idale= 0.165;
    }
    return $Idale;
}
