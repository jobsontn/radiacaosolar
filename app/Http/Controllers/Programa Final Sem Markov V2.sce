//tic();
//clc
//clear
//load('varia.dat');
//funcprot(0);
//exec(dirwork+'funcoes.sci');

//Funções 

//Função KD Diário.
function KD = ktkddiario(KT)
    if 0.17 <= KT & KT <= 0.75 then
        KD = (1.188-2.272*KT+9.473*(KT)^2-21.856*(KT)^3+14.648*(KT)^4);
    end
    if 0.75 < KT & KT <= 0.8 then
        KD = 0.632-0.54*KT;
    end
    if KT > 0.8 then
        KD = 0.2;
    end
    if KT < 0.17 then
        KD = 0.99;
    end
endfunction
//

//Função Costetaz
function Costetas = Costetasexp(Declinacao,Latitude,Betaa,Azimute,W)
    Costetas = sind(Declinacao)*sind(Latitude)*cosd(Betaa)-sind(Declinacao)*cosd(Latitude)*sind(Betaa)*cosd(Azimute)+cosd(Declinacao)*cosd(Latitude)*cosd(Betaa)*cosd(W)+ cosd(Declinacao)*sind(Latitude)*sind(Betaa)*cosd(Azimute)*cosd(W)+cosd(Declinacao)*sind(Betaa)*sind(Azimute)*sind(W)
endfunction
//

//Função transposição plano inclinado
function Fr= Fatortransposicaoliu(Betaa)
    Fr= (1 + cosd(Betaa))/2

endfunction
//

//Função kt aleatório diário
function Ktale = Ktalehorario(KT,KTM,LAMBDA)
    if 0.10 <= KT & KT <= 0.75 then
        Ktale= KTM+LAMBDA*(0.25-2*(KT-0.4)^2)
    end
    if 0.10> KT | KT >0.75 then
        Ktale= KTM
    end
    if KTM <=0 then
        Ktale = 0;
    end
endfunction
//

// Função 
function Idale = idalee(Ialecorrigido,ktalee)
    if ktalee <=0.8 & ktalee >=0.22 then
        Idale= Ialecorrigido*(0.9511-0.1604*ktalee+4.388*ktalee^2-16.638*ktalee^3+12.336*ktalee^4)
    end
    if ktalee<0.22 then
        Idale=Ialecorrigido*(1-0.09*ktalee)
    end
    if ktalee>0.8 then

//##########################################################
        Idale= Ialecorrigido*0.165  //corrigido por manoel
        //#############################################
    end
endfunction


//#################################################
//incluir esta função

//Função transposição plano inclinado Perez1

function Rd_Perez1 = Fatortransposicaoperez1(idale,tetaz,tetas,i0efetivo,Ialecorrigido,betaa)
//tetaz e tetas entram em radianos
alpha = 25 * (%pi/180)
betaa = betaa * (%pi/180)

        if idale >0 then        //Consideração 1 que idale seja maior que 0 para a equação não tender a infinito
            elinha = (idale+Ialecorrigido)/idale
        else
            elinha = 0
        end
        delta = idale/(i0efetivo*cos(tetaz))

        if 1<= elinha & elinha < 1.056 then
            F11 = -0.011
            F12 = 0.748
            F13 =-0.080
            F21 = -0.048
            F22 = 0.073
            F23 = -0.024
        end
        if 1.056<= elinha  & elinha <1.253 then
            F11 = -0.038
            F12 = 1.115
            F13 = -0.109
            F21 = -0.023
            F22 = 0.106
            F23 = -0.037
        end
        if 1.253<= elinha  & elinha <1.586 then
            F11 = 0.166
            F12 = 0.909
            F13 = -0.179
            F21 = 0.062
            F22 = -0.021
            F23 = -0.050
        end
        if 1.586<= elinha & elinha <2.134 then
            F11 = 0.419
            F12 = 0.646
            F13 = -0.262
            F21 = 0.140
            F22 = -0.167
            F23 = -0.042
        end
        if 2.134<= elinha & elinha <3.23 then
            F11 = 0.710
            F12 = 0.025
            F13 = -0.290
            F21 = 0.243
            F22 = -0.511
            F23 = -0.004
        end
        if 3.23 <= elinha & elinha <5.98 then
            F11 = 0.857
            F12 = -0.370
            F13 = -0.279
            F21 = 0.267
            F22 = -0.792
            F23 = 0.076
        end
        if 5.98<= elinha & elinha <10.08 then
            F11 = 0.743
            F12 = -0.073
            F13 = -0.228
            F21 = 0.231
            F22 = -1.180
            F23 = 0.199
        end
        if 10.08< elinha then
            F11 = 0.421
            F12 = -0.661
            F13 = 0.097
            F21 = 0.119
            F22 = -2.125
            F23 = 0.446
        end
        
        if elinha <> 0  then
        
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
        end
        if betaa - 0 then
            Rd_Perez1 = 1
        end
endfunction

//Fim Perez 1
//############################################



////Parte que será utilizada no programa na entrada

latitude1 = input("Digite a latitude do primeiro ponto: ");
longitude1 = input("Digite a longitude do primeiro ponto: ");
betaa = input(" Digite o valor do ângulo da superfície em graus: ");
azimute = input(" Digite o valor do ângulo azimutal em graus: ");  // 0º é sul 180º Norte

//latitude1 = 0.5166399
//longitude1 = -66.2440148

printf("\nProcurando dados....\n")

//arq = "C:\Users\pessoal\Dropbox\radiasol - scilab\Banco de dados Atlas Solarimétrico 2017\global_horizontal_means.csv";
arq = "C:\tools\global_horizontal_means.csv";

//isfile(arq) // Utilizar isso pra fazer as condicionais do 

sheet = csvRead (arq,";",",","double");
longitude2 = sheet(:,1);
latitude2 = sheet(:,2);
[l,c] = size(longitude2);
[l,c] = size(latitude2);

Raio_da_terra = 6371; // km

//Metódo Haversine para distância    //´Funciona os cálculos todos em radianos.
lat1 = latitude1 * (%pi / 180);
long1 = longitude1 * (%pi / 180);
lat2= latitude2* (%pi / 180);
long2= longitude2 * (%pi / 180);
dLat= (lat2-lat1)
dLon= (long2-long1)
A= sin(dLat/2) .* sin(dLat/2) + cos(lat1) .* cos(lat2) .* sin(dLon/2) .* sin(dLon/2);
C= 2 * atan(sqrt(A),sqrt(1-A));
D= Raio_da_terra *C; //Em km


proximo=find(D==min(D));
for i=(1:12)
    Hm(i)= sheet(proximo,3+i)
end


//área das constantes
i0 = 1367;  // em w/m2
HS=(5:20);
diajul = [15;45;74;105;135;166;196;227;258;288;319;349];
reflexao = 0.2      //Albedo

//fim da área das constantes

//Procesamento da geometria solar

for i = 1:12
    declinacao(i) = asind(-sin(23.45*%pi/180)*cos((%pi()/180)*(360/365.25)*(diajul(i)+10)));  //em graus eq. 
    ws(i) = acosd(-tand(declinacao(i))*tand(latitude1));  //em graus
    e0(i) = (1+0.033*cosd(360*diajul(i)/365.25));  //admensional
    h0(i) = (24/%pi)*i0*e0(i)*cosd(latitude1)*cosd(declinacao(i))*(sind(ws(i))-(%pi/180)*ws(i)*cosd(ws(i)))
    KT(i) = Hm(i)/h0(i);
    for j= 1:length(HS)
        w(j) = 15*(HS(j)-12)
        costetaz(i,j) = sind(declinacao(i))*sind(latitude1)+cosd(declinacao(i))*cosd(latitude1)*cosd(w(j))
        costetas(i,j)= Costetasexp(declinacao(i),latitude1,betaa,azimute,w(j));
        if costetas(i,j) < 0 then
            costetas(i,j) = 0;
        end
//##############################################
        tetas(i,j) = acos(costetas(i,j))
        tetaz(i,j) = acos(costetaz(i,j))
//inserir estas linhas, as funções são para radianos mesmo
//##############################################


        i0efetivo(i,j) = i0*e0(i)
        rr(i,j) = (1 - cosd(betaa))/2       // Gueymard 
    end
end

for i = 1:12

    kd(i) = ktkddiario(KT(i));
    hd(i) = kd(i)*Hm(i)
    hb(i) = Hm(i)-hd(i)
    a(i)= 0.409+0.5016*sind(ws(i)-60)
    b(i)= 0.6609-0.4767*sind(ws(i)-60)

    for j = 1:length(HS)
        w(j) = 15*(HS(j)-12)
        I(i,j) = (Hm(i)*(%pi*(a(i)+b(i)*cosd(w(j)))*(cosd(w(j))-cosd(ws(i)))/(24*(sind(ws(i))-(ws(i)*%pi/180)*cosd(ws(i))))));
        if I(i,j) < 0 then
            I(i,j) = 0;
        end

        lambda(i,j) =(rand()*(1+1)-1)

        ktm(i,j) = I(i,j)/(i0efetivo(i,j)*costetaz(i,j));//kt horário não aleatório
        ktale(i,j) = Ktalehorario(KT(i),ktm(i,j),lambda(i,j));
        if ktm(i,j)>0 then  //kt horário
            Iale(i,j) = ktale(i,j)*i0efetivo(i,j)*costetaz(i,j)
        else
            Iale(i,j)=0
        end
    end
end

for i = 1:12
    for j = 1:length(HS)
        Ialecorrigido(i,j)= (Iale(i,j)*Hm(i))/sum(Iale(i,:))      // Dando erro fazendo o mesmo valor de H apenas.
        if Ialecorrigido(i,j)<0 then
            Ialecorrigido(i,j)= 0
        end
        idale(i,j) = idalee(Ialecorrigido(i,j),ktale(i,j));
        //restante dos cálculos.
        ibale(i,j)= Ialecorrigido(i,j)-idale(i,j);   //não é a DNI
        ibtale(i,j)= ibale(i,j)*(costetas(i,j)/costetaz(i,j))

//####################################################
        //fr(i,j) = Fatortransposicaoliu(betaa)  //Fator de transposição para plano inclinado Liu Jordan
// comentar esta parte do programa

//##################################
        fr(i,j) = Fatortransposicaoperez1(idale(i,j),tetaz(i,j),tetas(i,j),i0efetivo(i,j),Ialecorrigido(i,j),betaa) // Fator Perez1
//inserir esta linha
//###################################


        idtale(i,j)= idale(i,j)*fr(i,j)
        pale(i,j) = rr(i,j)*Ialecorrigido(i,j)*reflexao
        Ghtale(i,j)= pale(i,j) + idtale(i,j) + ibtale(i,j)

    end
    diario(i) = sum(Ghtale(i,:));
end


mediadiariahorizontal = mean(Hm);
mediadiaria = mean(diario);

printf("A média horizontal diária é %f e a média inclinada é %f",mediadiariahorizontal,mediadiaria)


// área fim tic toc
tempo=toc();
hora1 = int(tempo/3600);
minuto1 = int(((tempo/3600)-hora1)*60);
segundo1 = ((((tempo/3600)-hora1)*60)-minuto1)*60;
printf('\n\n\nTempo de processamento: %g:%g:%g',hora1,minuto1,segundo1);

