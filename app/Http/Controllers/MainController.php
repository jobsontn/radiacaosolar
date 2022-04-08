<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  \App\Http\Requests\Ferramenta1Request;
use App\Models\Radiacao;

class MainController extends Controller
{

    /* -------------------------------------- Funções -------------------------------------- */
    function sind($angulo){
        return  sin(deg2rad($angulo));
    }
    
    function asind($angulo){
        return rad2deg(asin($angulo));
    }

    function cosd($angulo){
        return  cos(deg2rad($angulo));
    }

    function acosd($angulo){
        return rad2deg(acos($angulo));
    }

    function tand($angulo){
        return  tan(deg2rad($angulo));
    }

    function ktkddiario($KT){
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

    function Costetasexp($Declinacao,$Latitude,$Betaa,$Azimute,$W){
        /* Costetas = sind(Declinacao)
                    *sind(Latitude)
                    *cosd(Betaa)-sind(Declinacao)
                    *cosd(Latitude)
                    *sind(Betaa)
                    *cosd(Azimute)+cosd(Declinacao)
                    *cosd(Latitude)
                    *cosd(Betaa)
                    *cosd(W)+ cosd(Declinacao)
                    *sind(Latitude)
                    *sind(Betaa)
                    *cosd(Azimute)
                    *cosd(W)+cosd(Declinacao)
                    *sind(Betaa)
                    *sind(Azimute)
                    *sind(W) */
        $Costetas = $this->sind($Declinacao)
                        * $this->sind($Latitude)
                        * $this->cosd($Betaa) - $this->sind($Declinacao) 
                        * $this->cosd($Latitude) 
                        * $this->sind($Betaa) 
                        * $this->cosd($Azimute) + $this->cosd($Declinacao) 
                        * $this->cosd($Latitude) 
                        * $this->cosd($Betaa) 
                        * $this->cosd($W) + $this->cosd($Declinacao)
                        * $this->sind($Latitude)
                        * $this->sind($Betaa) 
                        * $this->cosd($Azimute)
                        * $this->cosd($W) + $this->cosd($Declinacao)
                        * $this->sind($Betaa) 
                        * $this->sind($Azimute)
                        * $this->sind($W);
        return $Costetas;
    }

    /* ------------------------------------------------------------------------------------ */

    public function ferramenta1_form()
    {
        return view('ferramenta1');
    }
    public function ferramenta1_form_admin()
    {
        return view('ferramenta1_form_admin');
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
        $inclinacao = 30; //betaa
        $orientacao = 60; //azimute
        
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

        //dd($proximo, $data[$proximo]);

        $radiacao = Radiacao::find($proximo + 1)->toArray();

        //dd($radiacao);

        //área das constantes
        $Hm=[
            $radiacao['01_JAN'],
            $radiacao['02_FEB'],
            $radiacao['03_MAR'],
            $radiacao['04_APR'],
            $radiacao['05_MAY'],
            $radiacao['06_JUN'],
            $radiacao['07_JUL'],
            $radiacao['08_AUG'],
            $radiacao['09_SEP'],
            $radiacao['10_OCT'],
            $radiacao['11_NOV'],
            $radiacao['12_DEZ']
        ];
        $i0 = 1367;  // em w/m2
        $HS=[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $diajul = [15,45,74,105,135,166,196,227,258,288,319,349];
        $reflexao = 0.2;      //Albedo

        //dd($Hm, $i0, $HS, $diajul, $reflexao);

        $latitude1 = rad2deg($latitude);
        for ($i = 0; $i < 12; $i++){
            //declinacao(i) = asind(-sin(23.45*%pi/180)*cos((%pi()/180)*(360/365.25)*(diajul(i)+10)));  //em graus eq.
            $declinacao[$i] = $this->asind(-1 * sin(deg2rad(23.45))*cos(deg2rad(1)*(360/365.25)*($diajul[$i]+10)));  //em graus eq.
            //ws(i) = acosd(-tand(declinacao(i))*tand(latitude1));  //em graus
            $ws[$i] = $this->acosd(-1 * $this->tand($declinacao[$i]) * $this->tand($latitude1));
            //e0(i) = (1+0.033*cosd(360*diajul(i)/365.25));  //admensional
            $e0[$i] = (1+0.033*$this->cosd(360*$diajul[$i]/365.25)); //admensional
            //h0(i) = (24/%pi)*i0*e0(i)*cosd(latitude1)*cosd(declinacao(i))*(sind(ws(i))-(%pi/180)*ws(i)*cosd(ws(i)))
            $h0[$i] = (24/pi()) * $i0 * $e0[$i] * $this->cosd($latitude1) * $this->cosd($declinacao[$i]) *  ($this->sind($ws[$i]) - (pi()/180) * $ws[$i] * $this->cosd($ws[$i]));
            //KT(i) = Hm(i)/h0(i);
            $KT[$i] = $Hm[$i] / $h0[$i];
            for ($j = 0; $j < count($HS); $j++){
                //w(j) = 15*(HS(j)-12)
                $w[$j] = 15 * ($HS[$j] - 12);
                //costetaz(i,j) = sind(declinacao(i))*sind(latitude1)+cosd(declinacao(i))*cosd(latitude1)*cosd(w(j))
                $costetaz[$i][$j] = $this->sind($declinacao[$i]) * $this->sind($latitude1) + $this->cosd($declinacao[$i]) * $this->cosd($latitude1) * $this->cosd($w[$j]);
                //costetas(i,j)= Costetasexp(declinacao(i),latitude1,betaa,azimute,w(j));
                $costetas[$i][$j] = $this->Costetasexp($declinacao[$i], $latitude1, $inclinacao, $orientacao, $w[$j]);
                //if costetas(i,j) < 0 then
                    //costetas(i,j) = 0;
                //end
                if($costetas[$i][$j] < 0){
                    $costetas[$i][$j] = 0;
                }
                //i0efetivo(i,j) = i0*e0(i)
                $i0efetivo[$i][$j] = $i0 * $e0[$i];
                //rr(i,j) = (1 - cosd(betaa))/2       // Gueymard 
                $rr[$i][$j] = (1 - $this->cosd($inclinacao)) / 2;  // Gueymard 
            }
        }

        //dd($declinacao, $h0, $KT, $latitude1);
        dd($rr);



        //fim da área das constantes

        return 10;
    }

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
