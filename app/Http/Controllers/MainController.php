<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  \App\Http\Requests\Ferramenta1Request;
use App\Models\Radiacao;
use Carbon\Carbon;

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
            $KD = (1.188-2.272*$KT+9.473*($KT)**2-21.856*($KT)**3+14.648*($KT)**4);
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

    function Ktalehorario($KT,$KTM,$LAMBDA) {
        $Ktale = 0;
        if(0.10 <= $KT && $KT <= 0.75){
            $Ktale= $KTM+$LAMBDA*(0.25-2*($KT-0.4)**2);
        }
        if(0.10> $KT || $KT >0.75){
            $Ktale= $KTM;
        }
        if($KTM <=0){
            $Ktale = 0;
        }
        return $Ktale;
    }

    function Fatortransposicaoliu($Betaa)
    {
        $Fr= (1 + $this->cosd($Betaa))/2;
        return $Fr;
    }

    function idalee($Ialecorrigido,$ktalee)
    {
        $Idale = 0;
        if($ktalee <=0.8 && $ktalee >=0.22){
            $Idale= $Ialecorrigido*(0.9511-0.1604*$ktalee+4.388*$ktalee**2-16.638*$ktalee**3+12.336*$ktalee**4);
        }
        if($ktalee<0.22){
            $Idale=$Ialecorrigido*(1-0.09*$ktalee);
        }
        if($ktalee>0.8){
            $Idale = $Ialecorrigido*0.165; //Corrigido por Manoel
        }
        return $Idale;
    }

    function transposicaoPerez1($idale,$tetaz,$tetas,$i0efetivo,$Ialecorrigido, $betaa) {
        //tetaz e tetas entram em radianos
        //alpha = 25 * (%pi/180)
        $alpha = deg2rad(25);
        //betaa = betaa * (%pi/180)
        $betaa = deg2rad($betaa);

        /* if idale >0 then        //Consideração 1 que idale seja maior que 0 para a equação não tender a infinito
            elinha = (idale+Ialecorrigido)/idale
        else
            elinha = 0
        end
        delta = idale/(i0efetivo*cos(tetaz))*/
        if ($idale > 0) {        //Consideração 1 que idale seja maior que 0 para a equação não tender a infinito
            $elinha = ($idale + $Ialecorrigido) / $idale;
        } else {
            $elinha = 0;
        }
        $delta = $idale / ($i0efetivo * cos($tetaz));
        /* if 1<= elinha & elinha < 1.056 then
            F11 = -0.011
            F12 = 0.748
            F13 =-0.080
            F21 = -0.048
            F22 = 0.073
            F23 = -0.024
        end */
        if (1 <= $elinha && $elinha < 1.056){
            $F11 = -0.011;
            $F12 = 0.748;
            $F13 =-0.080;
            $F21 = -0.048;
            $F22 = 0.073;
            $F23 = -0.024;
        }
        /* if 1.056<= elinha  & elinha <1.253 then
            F11 = -0.038
            F12 = 1.115
            F13 = -0.109
            F21 = -0.023
            F22 = 0.106
            F23 = -0.037
        end */
        if (1.056 <= $elinha  && $elinha < 1.253){
            $F11 = -0.038;
            $F12 = 1.115;
            $F13 = -0.109;
            $F21 = -0.023;
            $F22 = 0.106;
            $F23 = -0.037;
        }
        /* if 1.253<= elinha  & elinha <1.586 then
            F11 = 0.166
            F12 = 0.909
            F13 = -0.179
            F21 = 0.062
            F22 = -0.021
            F23 = -0.050
        end */
        if (1.253 <= $elinha  && $elinha < 1.586){
            $F11 = 0.166;
            $F12 = 0.909;
            $F13 = -0.179;
            $F21 = 0.062;
            $F22 = -0.021;
            $F23 = -0.050;
        }
        /* if 1.586<= elinha & elinha <2.134 then
            F11 = 0.419
            F12 = 0.646
            F13 = -0.262
            F21 = 0.140
            F22 = -0.167
            F23 = -0.042
        end */
        if (1.586 <= $elinha && $elinha < 2.134) {
            $F11 = 0.419;
            $F12 = 0.646;
            $F13 = -0.262;
            $F21 = 0.140;
            $F22 = -0.167;
            $F23 = -0.042;
        }
        /* if 2.134<= elinha & elinha <3.23 then
            F11 = 0.710
            F12 = 0.025
            F13 = -0.290
            F21 = 0.243
            F22 = -0.511
            F23 = -0.004
        end */
        if (2.134<= $elinha && $elinha < 3.23) {
            $F11 = 0.710;
            $F12 = 0.025;
            $F13 = -0.290;
            $F21 = 0.243;
            $F22 = -0.511;
            $F23 = -0.004;
        }
        /* if 3.23 <= elinha & elinha <5.98 then
            F11 = 0.857
            F12 = -0.370
            F13 = -0.279
            F21 = 0.267
            F22 = -0.792
            F23 = 0.076
        end */
        if (3.23 <= $elinha && $elinha < 5.98) {
            $F11 = 0.857;
            $F12 = -0.370;
            $F13 = -0.279;
            $F21 = 0.267;
            $F22 = -0.792;
            $F23 = 0.076;
        }
        /* if 5.98<= elinha & elinha <10.08 then
            F11 = 0.743
            F12 = -0.073
            F13 = -0.228
            F21 = 0.231
            F22 = -1.180
            F23 = 0.199
        end */
        if (5.98 <= $elinha && $elinha < 10.08) {
            $F11 = 0.743;
            $F12 = -0.073;
            $F13 = -0.228;
            $F21 = 0.231;
            $F22 = -1.180;
            $F23 = 0.199;
        }
        /* if 10.08< elinha then
            F11 = 0.421
            F12 = -0.661
            F13 = 0.097
            F21 = 0.119
            F22 = -2.125
            F23 = 0.446
        end */
        if (10.08 < $elinha) {
            $F11 = 0.421;
            $F12 = -0.661;
            $F13 = 0.097;
            $F21 = 0.119;
            $F22 = -2.125;
            $F23 = 0.446;
        }
        /* if elinha <> 0  then
            F2 = F21 + delta*F22+ tetaz* F23
            F1 = F11 + delta*F12+tetaz*F13
            if F1<= 0 then
                F1=0
            end
            if ((%pi/2)-alpha) < tetaz then
                Phi_h = ((%pi/2)-tetaz+alpha)/(2*alpha)
            else
                Phi_h = 1
            end
            Phi_c = ((%pi/2)-tetas+alpha)/(2*alpha)

            if tetaz < ((%pi/2)-alpha) then
                Xh = cos(tetaz)
            else
                Xh = Phi_h * sin(Phi_h*alpha)
            end

            if tetas < ((%pi/2)-alpha) then
                Xc = Phi_h * cos(tetas)
            elseif tetas > ((%pi/2)-alpha) & tetas <= ((%pi/2)+alpha) then
                Xc = Phi_h * Phi_c * sin(Phi_c * alpha)
            else
                Xc = 0
            end
            clinha = 2*Xh*(1-cos(alpha))
            alinha = 2*Xc*(1-cos(alpha))

            Rd_Perez1 = (1-F1)*((1+cos(betaa))/2)+ F1*(alinha/clinha) + F2*sin(betaa)
        else
             Rd_Perez1 = 0
        end */
        if ($elinha <> 0){
            $F2 = $F21 + $delta * $F22 + $tetaz * $F23;
            $F1 = $F11 + $delta * $F12 + $tetaz * $F13;
            if ($F1 <= 0) {
                $F1 = 0;
            }
            if (((pi() / 2) - $alpha) < $tetaz) {
                $Phi_h = ((pi()/2) - $tetaz + $alpha) / (2 * $alpha);
            } else {
                $Phi_h = 1;
            }
            $Phi_c = ((pi() / 2) - $tetas + $alpha) / (2 * $alpha);

            if ($tetaz < ((pi() / 2 ) - $alpha)) {
                $Xh = cos($tetaz);
            } else {
                $Xh = $Phi_h * sin($Phi_h * $alpha);
            }

            if ($tetas < ((pi()/2) - $alpha)) {
                $Xc = $Phi_h * cos($tetas);
            } elseif ($tetas > ((pi() / 2) - $alpha) && $tetas <= ((pi() / 2) + $alpha)) {
                $Xc = $Phi_h * $Phi_c * sin($Phi_c * $alpha);
            } else {
                $Xc = 0;
            }
            $clinha = 2 * $Xh *(1 - cos($alpha));
            $alinha = 2 * $Xc *(1 - cos($alpha));

            $Rd_Perez1 = (1 - $F1) * ((1 + cos($betaa)) / 2)+ $F1 * ($alinha / $clinha) + $F2 * sin($betaa);
        } else {
            $Rd_Perez1 = 0;
        }


        /* if betaa - 0 then
            Rd_Perez1 = 1
        end */
        if ($betaa == 0) {
            $Rd_Perez1 = 1;
        }
        return $Rd_Perez1;
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
        $mediadiariahorizontal = 8.5;
        //dd($validated);
        $resultado = $this->calculo_radiacao($validated['latitude'], $validated['longitude'], $validated['inclinacao'], $validated['orientacao']);
        //dd($resultado);
        return view('ferramenta1_form_admin')
                ->with($validated)
                ->with( compact('mediadiariahorizontal'))
                ->with( $resultado);
        dd("Teste", $validated);

    }

    public function calculo_radiacao($latitude, $longitude, $inclinacao, $orientacao )
    {
        $start = Carbon::now();
        /* $latitude = 0.5166399;
        $longitude = -66.2440148;
        $inclinacao = 30; //betaa
        $orientacao = 60; //azimute */

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
                //##############################################
                        // tetas(i,j) = acos(costetas(i,j))
                        // tetaz(i,j) = acos(costetaz(i,j))
                        $tetas[$i][$j]  = acos($costetas[$i][$j]);
                        $tetaz[$i][$j]  = acos($costetaz[$i][$j]);
                //inserir estas linhas, as funções são para radianos mesmo
                //##############################################

                //i0efetivo(i,j) = i0*e0(i)
                $i0efetivo[$i][$j] = $i0 * $e0[$i];
                //rr(i,j) = (1 - cosd(betaa))/2       // Gueymard
                $rr[$i][$j] = (1 - $this->cosd($inclinacao)) / 2;  // Gueymard
            }
        }

        $lambda = [
            [-0.7011799,-0.3596322,-0.5947891,-0.1002283,0.5415149,-0.5129552,-0.574777,-0.7801532,0.3962962,-0.1698187,0.0059638,0.5023214,0.9880294,-0.6342475,-0.3956165,-0.2429027],
			[0.4306397,0.9048307,-0.0592163,-0.6258117,-0.4885624,-0.1129868,0.4468156,0.752382,-0.9253358,-0.1413067,-0.3685534,-0.2635045,-0.7082451,0.3536759,0.0523959,-0.1992749],
			[-0.9941784,-0.3863637,0.5805388,0.9155901,0.3378542,-0.4140768,0.6447799,-0.9640309,0.7421403,-0.3637951,0.1448947,0.1477316,-0.3015964,0.4134595,-0.2801599,-0.1895377],
			[0.2281621,0.3048094,-0.9409658,0.1337792,0.4228034,-0.0823019,-0.0558965,-0.8752537,-0.8291198,-0.9730872,-0.2913996,0.3438789,-0.7278762,-0.5760513,-0.1968115,-0.1927563],
			[0.1256764,0.1062186,-0.8462032,0.0721517,-0.8199576,0.2436051,-0.999757,0.4439455,0.3322585,0.3598577,0.902825,-0.7586197,0.463782,-0.8336676,0.6043817,-0.9419017],
			[0.3005983,0.4855764,-0.5646778,0.9576973,0.7850474,-0.297745,-0.0508763,0.5398924,-0.8531315,0.1789237,-0.5242014,-0.1847278,-0.1390363,-0.9258566,0.2808297,-0.1738514],
			[0.6793805,-0.9995611,0.0170748,-0.0490005,0.9250131,0.9596023,-0.8428603,0.8300064,0.234522,0.9928389,-0.9062803,0.8491852,0.937973,-0.0167421,-0.4045894,-0.8793891],
			[0.2626944,-0.9517939,-0.6945123,0.8962355,-0.4511469,-0.0410546,-0.0288463,0.3528007,0.66425,-0.9748248,0.090756,0.6853434,0.805833,-0.1181036,0.6664718,0.4467953],
			[-0.12457,-0.3838785,0.7499626,0.0711765,-0.3828002,-0.3290735,-0.5315027,-0.4821177,0.7043018,-0.0356521,0.2190436,0.9745645,0.9622854,-0.3393773,-0.2821709,-0.4438945],
			[0.9166964,-0.9687274,-0.4071297,-0.062813,-0.1475958,-0.1564687,-0.7930293,-0.1440481,0.5721459,0.7136164,-0.6013125,0.4262603,0.0417905,0.8623446,-0.1712328,0.1960392],
			[0.1098211,0.7105905,-0.3804499,0.8892256,-0.5114639,0.7520894,-0.0251569,-0.2311962,-0.8155309,-0.8589163,0.4677615,0.5584361,0.5655276,-0.5676864,-0.3159604,-0.1398123],
			[0.2523505,0.9108502,-0.0916899,-0.9941554,-0.9786159,0.9492742,0.5408522,0.2793263,0.9071772,-0.1703033,-0.0676921,-0.552309,-0.3086053,-0.5318753,0.3238683,-0.4617641]
        ];

        for ($i = 0; $i < 12; $i++){
            //kd(i) = ktkddiario(KT(i));
            $kd[$i] = $this->ktkddiario($KT[$i]);
            //hd(i) = kd(i)*Hm(i)
            $hd[$i] = $kd[$i] * $Hm[$i];
            //hb(i) = Hm(i)-hd(i)
            $hb[$i] = $Hm[$i] - $hd[$i];
            //a(i)= 0.409+0.5016*sind(ws(i)-60)
            $a[$i] = 0.409 + 0.5016 * $this->sind($ws[$i] - 60);
            //b(i)= 0.6609-0.4767*sind(ws(i)-60)
            $b[$i] = 0.6609 - 0.4767 *  $this->sind($ws[$i] - 60);
            for ($j = 0; $j < count($HS); $j++){
                //w(j) = 15*(HS(j)-12)
                $w[$j] = 15 * ($HS[$j] - 12);
                // I(i,j) = (Hm(i)*(%pi*(a(i)+b(i)*cosd(w(j)))*(cosd(w(j))-cosd(ws(i)))/(24*(sind(ws(i))-(ws(i)*%pi/180)*cosd(ws(i))))));
                $I[$i][$j] = ($Hm[$i] * (pi() * ($a[$i] + $b[$i] * $this->cosd($w[$j])) * ($this->cosd($w[$j]) - $this->cosd($ws[$i])) / (24 * ($this->sind($ws[$i]) - ($ws[$i] * pi() / 180) * $this->cosd($ws[$i])))));
                /* if I(i,j) < 0 then
                    I(i,j) = 0;
                end */
                if($I[$i][$j] < 0){
                    $I[$i][$j] = 0;
                }

                //lambda(i,j) =(rand()*(1+1)-1) //-1 até 1
                $lambda[$i][$j] = mt_rand (-100000, 100000) / 100000; // -1 até 1

                //ktm(i,j) = I(i,j)/(i0efetivo(i,j)*costetaz(i,j));//kt horário não aleatório
                $ktm[$i][$j] = $I[$i][$j] / ( $i0efetivo[$i][$j] * $costetaz[$i][$j]); //kt horário não aleatório
                //ktale(i,j) = Ktalehorario(KT(i),ktm(i,j),lambda(i,j));
                $ktale[$i][$j] = $this->Ktalehorario($KT[$i],$ktm[$i][$j],$lambda[$i][$j]);
               /*  if ktm(i,j)>0 then  //kt horário
                    Iale(i,j) = ktale(i,j)*i0efetivo(i,j)*costetaz(i,j)
                else
                    Iale(i,j)=0
                end */
                if($ktm[$i][$j] > 0){
                    $Iale[$i][$j] = $ktale[$i][$j] * $i0efetivo[$i][$j] * $costetaz[$i][$j];
                } else {
                    $Iale[$i][$j] = 0;
                }
            }
        }
        //dd($lambda);

        $sum = function ($carry, $item) {
            $carry += $item;
            return $carry;
        };

        for ($i = 0; $i < 12; $i++){
            for ($j = 0; $j < count($HS); $j++){
                //Ialecorrigido(i,j)= (Iale(i,j)*Hm(i))/sum(Iale(i,:))      // Dando erro fazendo o mesmo valor de H apenas.
                $Ialecorrigido[$i][$j] = ($Iale[$i][$j] * $Hm[$i]) / array_reduce($Iale[$i], $sum);      // Dando erro fazendo o mesmo valor de H apenas.
                /* if Ialecorrigido(i,j)<0 then
                    Ialecorrigido(i,j)= 0
                end */
                if($Ialecorrigido[$i][$j] < 0){
                    $Ialecorrigido[$i][$j] = 0;
                }

                //idale(i,j) = idalee(Ialecorrigido(i,j),ktale(i,j));
                $idale[$i][$j] = $this->idalee($Ialecorrigido[$i][$j], $ktale[$i][$j]);

                //restante dos cálculos.

                //ibale(i,j)= Ialecorrigido(i,j)-idale(i,j)
                $ibale[$i][$j] = $Ialecorrigido[$i][$j] - $idale[$i][$j];
                //ibtale(i,j)= ibale(i,j)*(costetas(i,j)/costetaz(i,j))
                $ibtale[$i][$j] = $ibale[$i][$j] * ($costetas[$i][$j] / $costetaz[$i][$j]);
                //####################################################
                //fr(i,j) = Fatortransposicaoliu(betaa)  //Fator de transposição para plano inclinado Liu Jordan
                // $fr[$i][$j] = $this->Fatortransposicaoliu($inclinacao);  //Fator de transposição para plano inclinado Liu Jordan
                // comentar esta parte do programa
                //fr(i,j) = Fatortransposicaoperez1(idale(i,j),tetaz(i,j),tetas(i,j),i0efetivo(i,j),Ialecorrigido(i,j),betaa) // Fator Perez1
                $fr[$i][$j] = $this->transposicaoPerez1($idale[$i][$j], $tetaz[$i][$j], $tetas[$i][$j], $i0efetivo[$i][$j], $Ialecorrigido[$i][$j], $inclinacao);
                //####################################################
                //idtale(i,j)= idale(i,j)*fr(i,j)
                $idtale[$i][$j] = $idale[$i][$j] * $fr[$i][$j];
                //pale(i,j) = rr(i,j)*Ialecorrigido(i,j)*reflexao
                $pale[$i][$j] = $rr[$i][$j] * $Ialecorrigido[$i][$j] * $reflexao;
                //Ghtale(i,j)= pale(i,j) + idtale(i,j) + ibtale(i,j)
                $Ghtale[$i][$j] = $pale[$i][$j] + $idtale[$i][$j] + $ibtale[$i][$j];
            }
            //diario(i) = sum(Ghtale(i,:));
            $diario[$i] = array_reduce($Ghtale[$i], $sum);
        }

        //dd($fr);

        //mediadiariahorizontal = mean(Hm);
        $mediadiariahorizontal = array_sum($Hm)/count($Hm);
        //mediadiaria = mean(diario);
        $mediadiaria = array_sum($diario)/count($diario);


        $stop = Carbon::now();
        $duration = $start->diff($stop);
        //$duration = $start->diffForHumans($stop);
        $diff = $duration->format("%ss.%f");
        //dd($declinacao, $h0, $KT, $latitude1);
        //dd($mediadiariahorizontal, $mediadiaria, $diff);


        //fim da área das constantes

        return compact('diff', 'mediadiariahorizontal', 'mediadiaria', 'Hm', 'diario');
    }

}
