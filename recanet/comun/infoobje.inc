<? 
  include_once "comun/cantboni.fnc";
  
  print( "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/infocata.js") . "'></SCRIPT>\n\n");
   
  # Hallamos codiobje, necesario para casi todos los casos 
  $codiobje = sql ("SELECT codiobje 
                    FROM carg 
                    WHERE codiayun = '$codiayun' AND 
                          codiconc = '$codiconc' AND 
                          ejer     = '$ejer'     AND 
                          numedocu = '$numedocu'"); 
 
  # Buscamos la descripcion del contenido del objeto tributario 
  $descobje = sql ("SELECT clasobje.nombcodiobje 
                    FROM clasobje, conctrib 
                    WHERE conctrib.codiayun = '$codiayun' AND 
                          conctrib.codiconc = '$codiconc' AND 
                          conctrib.codiclas = clasobje.codiclas"); 
 
  switch ($tipoobje) { 
    case 'OICV': 
      $query = "SELECT bast, marcmode, plaz, capa, cili, pote, calc, 
                       servvehi.nomb as serv, tipovehi.nomb as tipo, 
                       porcboni, impoingr, refeingr, fechingr, causboni
                FROM   (cargoicv LEFT JOIN servvehi 
                        ON cargoicv.codiserv = servvehi.abre) 
                       LEFT JOIN tipovehi 
                       ON cargoicv.coditipo = tipovehi.abre 
                WHERE  cargoicv.codiconc = '$codiconc' AND 
                       cargoicv.codiayun = '$codiayun' AND 
                       cargoicv.ejer     = '$ejer' AND 
                       cargoicv.numedocu = '$numedocu'"; 
      // print "<b>Consulta</b>: $query<br>\n"; 
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value]; 
      ?> 
      <tr> 
        <td colspan=4 class=izqform> 
          INFORMACIÓN DE VEHÍCULOS (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código tributario</td> 
        <td align=center class=derform><? print $codiobje ?></td> 
        <td class=izqform>Bastidor</td> 
        <td class=derform><? print "$dato[bast]"; ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Marca-modelo</td> 
        <td class=derform><? print "$dato[marcmode]"; ?></td> 
        <td class=izqform>Tipo vehículo</td> 
        <td class=derform><? print "$dato[tipo]"; ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Servicio</td> 
        <td class=derform><? print "$dato[serv]"; ?></td> 
        <td class=izqform>Plazas</td> 
        <td class=derform><? print "$dato[plaz]"; ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Carga</td> 
        <td class=derform><? print "$dato[capa] kg"; ?></td> 
        <td class=izqform>Cilindrada</td> 
        <td class=derform><? print "$dato[cili] cc"; ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Potencia</td> 
        <td class=derform><? print "$dato[pote] hp"; ?></td> 
        <td class=izqform>Categoría</td> 
        <td class=derform><? print "$dato[calc]"; ?></td> 
      </tr> 

      <? if ( $dato[porcboni] || $dato[causboni] ) { ?> 
      <tr> 
        <td class=izqform>Beneficios tributarios</td> 
        <? if ( $dato[causboni] ) {
             print "<td class=derform colspan = 2>".$dato[causboni]."</td>\n";
           }
        ?>
        <td class=derform <? if ( !$dato[causboni] ) print "colspan=3"; ?>>
        <? print $dato[porcboni]."%"; ?>
        </td>
      </tr> 
      <? } ?> 

      <? if ( $dato[impoingr] ) { ?> 
      <tr> 
        <td class=izqform>Ingreso a cuenta</td> 
        <td class=derform><?print impoboni( $dato[impoingr] ) ?></td> 
        <td class=izqform>Referencia del ingreso</td> 
        <td class=derform><?print Mostrarfecha($dato[fechingr])." ".$dato[refeingr] ?></td> 
      </tr> 
      <? } ?> 
      <? 
      break; 
 
 
    case 'OIBIURBA': 
      $query = "SELECT siglvias.siglviax as siglviassiglviax, 
                       vias.nomb as viasnomb, 
                       cargoibiurba.nume as cargoibiurbanume, 
                       cargoibiurba.letr as cargoibiurbaletr, 
                       cargoibiurba.esca as cargoibiurbaesca, 
                       cargoibiurba.plan as cargoibiurbaplan, 
                       cargoibiurba.puer as cargoibiurbapuer, 
                       cargoibiurba.valosuel as cargoibiurbavalosuel, 
                       cargoibiurba.valocons as cargoibiurbavalocons, 
                       cargoibiurba.valocata as cargoibiurbavalocata, 
                       cargoibiurba.base as cargoibiurbabase, 
                       cargoibiurba.nomb as cargoibiurbanomb, 
                       cargoibiurba.idenloca as cargoibiurbaidenloca, 
                       cargoibiurba.porcboni as cargoibiurbaporcboni, 
                       cargoibiurba.causboni as cargoibiurbacausboni, 
                       cargoibiurba.numecarg as cargoibiurbanumecarg, 
                       cargoibiurba.caracont as cargoibiurbacaracont, 
                       cargoibiurba.impoingr as cargoibiurbaimpoingr, 
                       cargoibiurba.fechingr as cargoibiurbafechingr, 
                       cargoibiurba.refeingr as cargoibiurbarefeingr
                FROM   cargoibiurba 
                       LEFT JOIN 
                       (vias LEFT JOIN siglvias 
                        ON vias.codisigl = siglvias.codisigl) 
                       ON cargoibiurba.codiviax = vias.codiviax, 
                       carg 
                       -- carg LEFT JOIN subxconc ON 
                             -- carg.anio = subxconc.ejer 
                WHERE  cargoibiurba.codiayun = '$codiayun' AND 
                       cargoibiurba.codiconc = '$codiconc' AND 
                       cargoibiurba.ejer     = '$ejer'     AND 
                       cargoibiurba.numedocu = '$numedocu' AND 
                       carg.codiayun         = '$codiayun' AND 
                       carg.codiconc         = '$codiconc' AND 
                       carg.ejer             = '$ejer'     AND 
                       carg.numedocu         = '$numedocu'"; 
      // print "<b>La consulta para OIBIURBA es: $query</b><br>\n"; 
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value]; 
 
      $query = "SELECT porc 
                FROM   tari, subxconc, carg, cargoibiurba 
                WHERE  tari.codisubc         = subxconc.codisubc AND 
                       tari.ejer             = carg.anio         AND 
                       carg.codiayun         = '$codiayun'       AND 
                       carg.codiconc         = '$codiconc'       AND 
                       carg.numedocu         = '$numedocu'       AND 
                       carg.ejer             = '$ejer'           AND 
                       cargoibiurba.codiconc = subxconc.codiconc AND 
                       cargoibiurba.codiayun = '$codiayun'       AND 
                       cargoibiurba.codiconc = '$codiconc'       AND 
                       cargoibiurba.numedocu = '$numedocu'       AND 
                       cargoibiurba.ejer     = '$ejer'"; 
      // print "<b>Consulta</b>: $query<br>\n"; 
      $tariporc = sql ($query); 

      //--- Icono pa'buscar en el mapa
      print "<div class=solopantalla><hr>";

      // Finca o cargo
      if ($dato[cargoibiurbanumecarg]) {
        $func = "infocata('".substr($codiobje,0,14)."','".$dato[cargoibiurbanumecarg]."');";
      } else {
        $func ="infocata('".substr($codiobje,0,14)."');";
      }

      // Muestra el plano del inmueble en uso
      print ("<a href=''
               onclick=\"".$func."
                        return false; \">
            <img src='" . cheqroot("imag/mapapeque.gif") . "' border='0' width='24'
              height='25' align='middle'  alt='Informe de finca/cargo'></a>\n\n");

      //--- Fin icono pa'buscar en el mapa
      print "<hr></div>";

      ?> 
      <tr> 
        <td colspan=6 class=izqform> 
          INFORMACIÓN DE LA FINCA URBANA (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código tributario</td> 
        <td align=center colspan=3 class=derform><? print $codiobje . " Cargo " .  $dato[cargoibiurbanumecarg] . " C. Control " . $dato[cargoibiurbacaracont]?></td> 
        <td class=izqform>Tipo gravamen</td> 
        <td class=derform><?print "$tariporc %"?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Domicilio tributario</td> 
        <td class=derform colspan=5> 
          <?print mostrarDireccion ($dato[siglviassiglviax], 
                                    $dato[viasnomb], 
                                    $dato[cargoibiurbanume], 
                                    $dato[cargoibiurbaplan], 
                                    $dato[cargoibiurbaletr], 
                                    $dato[cargoibiurbaesca], 
                                    $dato[cargoibiurbapuer])?> 
        </td> 
      </tr> 
      <? if ($dato[cargoibiurbanomb]) { ?> 
        <tr> 
          <td class=izqform>Nombre del inmueble</td> 
          <td class=derform colspan=3><?print $dato[cargoibiurbanomb]?></td> 
          <td class=derform colspan=2> 
          <? if ($dato[cargoibiurbaidenloca]) {  
               print $dato[cargoibiurbaidenloca]; 
             } ?> 
          </td> 
        </tr> 
      <? } ?> 
      <? if ( ( $dato[cargoibiurbavalosuel] ) || ( $dato[cargoibiurbavalocons] ) ) { ?> 
      <tr> 
        <td class=izqform>Valor suelo</td> 
        <td class=derform colspan=2><?print impoboni ($dato[cargoibiurbavalosuel])?></td> 
        <td class=izqform>Valor construcción</td> 
        <td class=derform colspan=2><?print impoboni ($dato[cargoibiurbavalocons])?></td> 
      </tr> 
      <? } ?> 
      <tr> 
        <td class=izqform>Valor catastral</td> 
        <td class=derform colspan=2><?print impoboni ($dato[cargoibiurbavalocata])?></td> 
        <td class=izqform>Base liquidable</td> 
        <td class=derform colspan=2><?print impoboni ($dato[cargoibiurbabase])?></td> 
      </tr> 

      <? if ( $dato[cargoibiurbaporcboni] || $dato[cargoibiurbacausboni] ) { ?> 
      <tr> 
        <td class=izqform>Beneficios tributarios</td> 
        <? if ( $dato[cargoibiurbacausboni] ) {
             print "<td class=derform colspan = 3>".$dato[cargoibiurbacausboni]."</td>\n";
            } 
        ?>
        <td class=derform <? if ( !$dato[cargoibiurbacausboni] ) print "colspan=5"; else print "colspan=2";?>>
        <? print $dato[cargoibiurbaporcboni]."%"; ?>
        </td>
      </tr> 
      <? } ?> 

      <? if ( $dato[cargoibiurbaimpoingr] ) { ?> 
      <tr> 
        <td class=izqform>Ingreso a cuenta</td> 
        <td class=derform colspan=2><?print impoboni( $dato[cargoibiurbaimpoingr] ) ?></td> 
        <td class=izqform>Referencia del ingreso</td> 
        <td class=derform colspan=2><?print Mostrarfecha($dato[cargoibiurbafechingr])."&nbsp;&nbsp;&nbsp;".$dato[cargoibiurbarefeingr] ?></td> 
      </tr> 
      <? }  
         $reviurba = sql ("SELECT reviurba FROM ayun WHERE codiayun = '$codiayun'"); 
         if ($reviurba != '') {  
           echo "<tr> 
                   <td class=derform colspan=6 align=center> 
                     Municipio revisado por el Catastro en $reviurba 
                   </td> 
                 </tr>"; 
         } ?> 
      <? 
      break; 
 
 
    case 'OIBIRUST': 
      // Para la funcion supeboni()
      include_once "comun/supe.fnc";
 
      $query = "SELECT coefpart, porcboni, impoingr, fechingr, refeingr, causboni 
                FROM   cargoibirust 
                WHERE  codiayun = '$codiayun' AND 
                       codiconc = '$codiconc' AND 
                       ejer     = '$ejer'     AND 
                       numedocu = '$numedocu'"; 
 
      $dato = sql ($query); 

      if (is_array ($dato)) {
        $dato = each ($dato); 
        $dato = $dato[value]; 
      }
 
      // Query para las parcelas 
      $query = "SELECT count(*) as contparc,  
                       sum(valocata) as valocata, 
                       sum(supe) as supe 
                FROM cargoibirustparc 
                WHERE codiayun = '$codiayun'  
                  AND codiconc = '$codiconc'  
                  AND ejer = '$ejer'  
                  AND numedocu = '$numedocu'"; 
 
      $datoparc = sql ($query); 

      if (is_array ($datoparc)) {
        $datoparc = each ($datoparc); 
        $datoparc = $datoparc[value]; 
      }
      
      // Tipo de gravamen 
      $query = "SELECT porc 
                FROM   tari, subxconc, carg, cargoibirust 
                WHERE  tari.codisubc         = subxconc.codisubc AND 
                       tari.ejer             = carg.anio         AND 
                       carg.codiayun         = '$codiayun'       AND 
                       carg.codiconc         = '$codiconc'       AND 
                       carg.numedocu         = '$numedocu'       AND 
                       carg.ejer             = '$ejer'           AND 
                       cargoibirust.codiconc = subxconc.codiconc AND 
                       cargoibirust.codiayun = '$codiayun'       AND 
                       cargoibirust.codiconc = '$codiconc'       AND 
                       cargoibirust.numedocu = '$numedocu'       AND 
                       cargoibirust.ejer     = '$ejer'"; 
      $tariporc = sql ($query); 
      ?> 
      <tr> 
        <td colspan=4 class=izqform> 
          INFORMACIÓN DE LA FINCA RÚSTICA (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código tributario</td> 
        <td align=center colspan=3 class=derform><? print $codiobje ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Superficie total/titular</td> 
        <td class=derform><?print supeboni($datoparc[supe]); ?></td> 
        <td class=izqform>Valor catastral total/titular</td> 
        <td class=derform><?print impoboni ($datoparc[valocata])?></td> 
      </tr> 
      <? if ( $tariporc > 0 ) { ?> 
      <tr> 
        <td class=izqform>Tipo de gravamen</td> 
        <td class=derform><?print $tariporc." %" ?></td> 
        <td class=izqform>Coef. participación</td> 
        <td class=derform><?print $dato[coefpart]." %" ?></td> 
      </tr> 
      <? } ?> 
      <? if ( $dato[porcboni] || $dato[causboni] ) { ?> 
      <tr> 
        <td class=izqform>Beneficios tributarios</td> 
        <? if ( $dato[causboni] ) {
             print "<td class=derform colspan = 2>".$dato[causboni]."</td>\n";
           }
        ?>
        <td class=derform <? if ( !$dato[causboni] ) print "colspan=3"; ?>>
        <? print $dato[porcboni]."%"; ?>
        </td>
      </tr> 
      <? } ?> 
      <? if ( $dato[impoingr] ) { ?> 
      <tr> 
        <td class=izqform>Ingreso a cuenta</td> 
        <td class=derform><?print impoboni( $dato[impoingr] ) ?></td> 
        <td class=izqform>Referencia del ingreso</td> 
        <td class=derform><?print Mostrarfecha($dato[fechingr])." ".$dato[refeingr] ?></td> 
      </tr> 
      <? } ?> 
      <? 
 
      # Ahora la consulta para las diferentes parcelas (tabla cargoibirustparc) 
      // Se muestra la informacion de las parcelas dependiendo del valor de  
      // la variable $procllam, que puede ser "ventinfo", "notiindi" o "cartpago". 
      // Si el valor es "ventinfo", se muestra la informacion de las parcelas. 
      // Si el valor es "cartpago", se indica la cantidad de parcelas, y en otra  
      // hoja se muestra la informacion detallada de las parcelas. 
      // Si el valor es "notiindi", solo se indica la cantidad de parcelas. 
      if ( $procllam == "ventinfo" ) { 
        include "comun/infoparc.inc"; 
      } else { 
        ?> 
        <tr> 
        <td class=izqform colspan = 4 align='center'><? print $datoparc[contparc] ?> Parcelas</td> 
        </tr> 
        <? 
      } 
      break; 
 
 
    case 'OTPP': 
      $query = "SELECT siglviax, vias.nomb as nomb, refetasa, nume,  
                       letr, esca, plan, puer, datoactu, datoante, 
                       porcboni, impoingr, refeingr, fechingr, causboni 
                FROM   (cargotpp LEFT JOIN vias ON 
                        cargotpp.codiviax = vias.codiviax) 
                       LEFT JOIN siglvias ON 
                       siglvias.codisigl = vias.codisigl 
                WHERE  cargotpp.codiconc = '$codiconc' AND 
                       cargotpp.codiayun = '$codiayun' AND 
                       cargotpp.ejer     = '$ejer'     AND 
                       cargotpp.numedocu = '$numedocu'"; 
      // print "<b>Consulta OTPP</b>: $query<br>\n"; 
      $dato = sql ($query); 
      ?> 
      <tr> 
        <td colspan=4 class=izqform> 
          INFORMACIÓN DE TASAS Y PRECIOS PÚBLICOS (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <? 
      if (is_array ($dato)) { 
        $dato = each ($dato); 
        $dato = $dato[value]; 
        ?> 
        <tr> 
          <td class=izqform <? if (!$dato[refetasa]) print "colspan=2"; ?>>Código tributario</td> 
          <td align=center class=derform <? if (!$dato[refetasa]) print "colspan=2"; ?>> 
            <? print $codiobje ?> 
          </td> 
          <? if ($dato[refetasa]) { ?> 
            <td class=izqform>Referencia</td> 
            <td class=derform><? print $dato[refetasa]; ?></td> 
          <? } ?> 
        </tr> 
        <? if ($dato[nomb]) { ?> 
        <tr> 
          <td class=izqform>Domicilio tributario</td> 
          <td class=derform colspan=3><? print mostrarDireccion ($dato[siglviax], $dato[nomb], $dato[nume], $dato[plan], $dato[letr], $dato[esca], $dato[puer]); ?></td> 
        </tr> 
        <? } ?> 
        <? if ($dato[datoactu] || $dato[datoante]) { ?> 
        <tr> 
          <td class=izqform>Lectura actual</td> 
          <td class=derform><? print "$dato[datoactu]"; ?></td> 
          <td class=izqform>Lectura anterior</td> 
          <td class=derform><? print "$dato[datoante]"; ?></td> 
        </tr> 
        <? } ?> 
        <? if ( $dato[porcboni] || $dato[causboni] ) { ?> 
        <tr> 
          <td class=izqform>Beneficios tributarios</td> 
          <? if ( $dato[causboni] ) {
               print "<td class=derform colspan = 2>".$dato[causboni]."</td>\n";
             }
          ?>
          <td class=derform <? if ( !$dato[causboni] ) print "colspan=3"; ?>>
          <? print $dato[porcboni]."%"; ?>
          </td>
        </tr> 
        <? } ?> 
        <? if ( $dato[impoingr] ) { ?> 
        <tr> 
          <td class=izqform>Ingreso a cuenta</td> 
          <td class=derform><?print impoboni( $dato[impoingr] ) ?></td> 
          <td class=izqform>Referencia del ingreso</td> 
          <td class=derform><?print Mostrarfecha($dato[fechingr])." ".$dato[refeingr] ?></td> 
        </tr> 
        <? } ?> 
        <tr> 
          <td colspan=4> 
            <? 
              $anio = sql ("SELECT anio 
                              FROM carg 
                              WHERE codiayun = '$codiayun' AND 
                                   codiconc = '$codiconc' AND 
                                   numedocu = '$numedocu' AND 
                                   ejer     = '$ejer'"); 
              $query = "SELECT codisubc, busc, oper 
                        FROM   cargotppcalc LEFT JOIN subxconc 
                               ON (cargotppcalc.codiconc = 
                                     subxconc.codiconc AND 
                                   cargotppcalc.abresubc = 
                                     subxconc.abre) 
                        WHERE  cargotppcalc.codiayun = '$codiayun' AND 
                               cargotppcalc.codiconc = '$codiconc' AND 
                               cargotppcalc.numedocu = '$numedocu' AND 
                               cargotppcalc.ejer     = '$ejer'"; 
              // print "<b>Consulta</b>: $query<br>\n"; 
              $dato = sql ($query); 
              if (is_array ($dato)) { 
              ?>
              <table width="100%">
                <tr>
                  <td class=izqform>Subconcepto</td>
                  <td class=izqform>Operador</td>
                </tr>
              <?
                while ($valo = each ($dato)) { 
                  $valo = $valo[value];                    
                  $reg = sql ("SELECT nomb FROM tari WHERE cate = '$valo[busc]' AND codisubc='$valo[codisubc]'"); 
                  if (!empty($reg)){ 
                     if (is_array ($reg)) { 
                        $val = each ($reg); 
                        $val = $val[value]; 
                        $val = $val[nomb]; 
                     }else $val=$reg; 
                     echo "<tr><td class=derform>".$val."</td>"; 
                     echo "<td class=derform>".$valo[oper]."</td></tr>";                    
                  } 
                } 
           ?> </table> <?
              } 
              ?> 
          </td> 
        </tr> 
        <? 
      } 
      break; 
 
    case 'OIAE': 
      $query = "SELECT sigl, cargoiae.nomb as nomb, nume, letr, esca, 
                       plan, puer, tari, codigrup, fechinic, supetota, 
                       superect, supecomp, cuotmaqu, cuottari, indisitu, 
                       tipoacti.nomb as tipoacti, 
                       oiaeepig.nomb as descepig, 
                       periextr, fechcese, porcboni, cargoiae.causboni, cargoiae.tipooper, 
                       cargoiae.impoingr, cargoiae.fechingr, cargoiae.refeingr,
                       impoincn, catepond, nombcome
                FROM   (cargoiae INNER JOIN tipoacti 
                        ON cargoiae.tipoacti = tipoacti.coditipo) 
                       LEFT JOIN oiaeepig 
                       ON (cargoiae.codigrup = oiaeepig.grup AND 
                           cargoiae.tari     = oiaeepig.secc) 
                WHERE  codiayun = '$codiayun' AND 
                       codiconc = '$codiconc' AND 
                       ejer     = '$ejer'     AND 
                       numedocu = '$numedocu'"; 
      // print "<b>La consulta de OIAE es: $query</b><br>\n"; 
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value]; 
      ?> 
      <tr> 
        <td colspan=6 class=izqform> 
          INFORMACIÓN DE LA ACTIVIDAD ECONÓMICA (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código tributario</td> 
        <td class=derform><? print $codiobje ?></td> 
        <td class=izqform>Tipo</td> 
        <td class=derform><? print $dato[tipoacti] ?></td> 
        <td class=izqform>Extracción</td> 
        <td class=derform> 
        <?switch ( $dato[periextr] ) { 
            case '0A': print "ANUAL"; break; 
            case '1T': print "1TRIMESTRE"; break; 
            case '2T': print "2TRIMESTRE"; break; 
            case '3T': print "3TRIMESTRE"; break; 
            case '4T': print "4TRIMESTRE"; break; 
          } 
        ?></td> 
      </tr> 
      <tr> 
        <td colspan=1 class=izqform>Domicilio</td> 
        <td colspan=3 class=derform><?print mostrarDireccion ($dato[sigl], 
                                                              $dato[nomb], 
                                                              $dato[nume], 
                                                              $dato[plan], 
                                                              $dato[letr], 
                                                              $dato[esca], 
                                                              $dato[puer])?></td> 
         <td class=izqform>I.Neto Cifra Negocio</td> 
         <td class=derform align='center'>
           <? if ( $dato[impoincn] ) print $dato[impoincn]." &euro;"; else print "SIN DECLARAR"; ?>
         </td> 
      </tr> 
      <tr> 
        <td class=izqform>Índice de situación</td> 
        <td class=derform><?print $dato[indisitu]?></td> 
        <td class=izqform>Fecha inicio</td> 
        <td class=derform><?print mostrarFecha ($dato[fechinic])?></td> 
        <td class=izqform> 
        <? 
          if ( $dato[tipooper] == "A" ) { 
            print "Fecha variación"; 
          } elseif ( $dato[tipooper] == "B" ) { 
            print "Fecha cese"; 
          } else { 
            print "Fecha variación/cese"; 
          } 
        ?></td> 
        <td class=derform><?print mostrarFecha ($dato[fechcese])?></td> 
      </tr> 
      <tr> 
        <td colspan=2 class=izqform>Grupo o epígrafe</td> 
        <td colspan=4 class=derform><?print "$dato[tari] $dato[codigrup] $dato[descepig]"?></td> 
      </tr> 
      <? 
        // Obtengo los porcentajes empleados en el calculo de la deuda (tipo de calculo PU y PSC) 
        // que se encuentran en la tabla tari. 
        // Necesito el año contraído para enlazar con la tabla tari.
        $anio = sql ("SELECT anio FROM carg WHERE codiayun = '$codiayun' AND 
                      codiconc = '$codiconc' AND numedocu = '$numedocu' AND ejer = '$ejer'"); 
        // Primer subconcepto, el de tipo PU
        $tariporc = "SELECT tari.nomb, tari.porc FROM subxconc, tari  
                     WHERE codiconc = '$codiconc' AND ejer = '$anio' AND coditipo = 'PU'
                     AND subxconc.codisubc=tari.codisubc;"; 
        $sql = sql( $tariporc ); 
        $j = 0;  // Contador de subconceptos
        if ( is_array( $sql ) ) { 
          // Debe haber dado un solo registro
          $subc = each( $sql );
            $subc = $subc[value]; 
            $vectsubc[$j][nomb] = $subc[nomb]; 
            $vectsubc[$j][porc] = $subc[porc]; 
            $j++; 
          } 
        // Segundo subconcepto, el de tipo PSC
        $tariporc = "SELECT tari.nomb, tari.porc FROM subxconc, tari  
                     WHERE codiconc = '$codiconc' AND ejer = '$anio' AND coditipo = 'PSC'
                     AND subxconc.codisubc=tari.codisubc AND tari.cate = '$dato[catepond]';"; 
        $sql = sql( $tariporc ); 
        if ( is_array( $sql ) ) { 
          //$j = 0; 
          //while( $subc = each( $sql ) ) { 
            $subc = each( $sql ); 
            $subc = $subc[value]; 
            $vectsubc[$j][nomb] = $subc[nomb]; 
            $vectsubc[$j][porc] = $subc[porc]; 
            $j++; 
          //} 
        } 
      ?> 
      <tr> 
        <td class=izqform>Cuota mínima</td> 
        <td nowrap class=derform><?print impoboni ($dato[cuottari])?></td> 
        <td class=izqform><? $str = $vectsubc[0][nomb]; print strtolower($str); ?></td> 
        <td nowrap class=derform><?print $vectsubc[0][porc]."%"; ?></td> 
        <td class=izqform><? $str = $vectsubc[1][nomb]; print strtolower($str); ?></td> 
        <td nowrap class=derform><?print $vectsubc[1][porc]."%"; ?></td> 
      </tr> 
      <? if ($dato[supetota] || $dato[superect] || $dato[supecomp] || $dato[nombcome]) { ?> 
      <tr> 
        <? if ($dato[supetota] or $dato[superect] or $dato[supecomp]) { ?> 
          <td class=izqform 
          <? if ( !$dato[nombcome] ) { 
               print " colspan=4";
             } else {
               print " colspan=2";
             }
          ?>
          >Sup. Total|Rectificada|Computable</td> 
          <td class=derform 
          <? if ( !$dato[nombcome] ) print " colspan='2'"; ?>>
                            <?print $dato[supetota]?> m<sup>2</sup>| 
                            <?print $dato[superect]?> m<sup>2</sup>| 
                            <?print $dato[supecomp]?> m<sup>2</sup></td> 
        <? } ?> 
        <? if ( $dato[nombcome] ) { ?> 
          <td class=izqform
          <? if ( !$dato[supetota] && !$dato[superect] && !$dato[supecomp] ) {
               print " colspan='3'";
             } ?> 
          >Nombre</td> 
          <td class=derform 
          <? if ( !$dato[supetota] && !$dato[superect] && !$dato[supecomp] ) {
               print " colspan='3'";
             } else {
               print " colspan='2'";
             }
          ?> 
          ><?print $dato[nombcome]?></td> 
        <? } ?> 
      </tr> 
        <? } ?> 
      <tr> 
        <? 
          // Busco la cuota provincial y municipal que están en la tabla cargdeud
          $conssubc = "SELECT nomb, subxdeud FROM cargdeud CD INNER JOIN subxconc SC 
                       ON CD.codisubc = SC.codisubc WHERE codiayun = '$codiayun' AND
                       CD.codiconc = '$codiconc' AND ejer = '$ejer' AND numedocu = '$numedocu'";
          $resusubc = sql( $conssubc );
          if ( $resusubc ) { 
            $nombsubc = "";
            $valosubc = "";
            while ( $subcvalo = each( $resusubc ) ) {
              $subcvalo = $subcvalo[value];
              ?>
              <td class=izqform colspan=2><? print $subcvalo[nomb]; ?></td> 
              <td nowrap class=derform><? print impoboni($subcvalo[subxdeud]); ?></td>
              <?
            }
          } ?> 
      </tr> 
      <? if ( $dato[causboni] || $dato[impoingr] || ( $dato[porcboni] > 0 ) ) { ?> 
         <tr>
         <? 
           // Determinar el numero de columnas que ocupa cada uno, cuando 
           // alguno de los 3 campos (causboni, porcboni, impoingr) esté a 0. 
           // Inicialmente, supongo que se muestran los 3 campos. 
           $camp = 3; 
           // Si hemos llegado aqui, no se puede cumplir que los 3 campos estén a 0. 
           if ( !$dato[causboni] || !$dato[impoingr] || !( $dato[porcboni] > 0 ) ) {  
             // Si alguno de los campos, está a 0, aqui considero que solo uno de  
             // los campos está a 0, y en el siguiente IF compruebo si son 2 
             // los campos que están a 0. 
             $camp = 2; 
           } 
           if ( ( !$dato[causboni] && !$dato[impoingr] ) ||  
                ( !$dato[causboni] && !( $dato[porcboni] > 0 ) ) || 
                ( !$dato[impoingr] && !( $dato[porcboni] > 0 ) ) ) { 
             // Solo uno de los campos es distinto de 0 
             $camp = 1; 
           } 
         ?> 
         <? if ( $dato[impoingr] ) { ?> 
          <td class=izqform<? if ( $camp == 1 ) print " colspan=2"; ?>>Ingreso a cuenta</td> 
          <td class=derform  
            <? 
              if ( $camp == 2 ) print " colspan=2"; 
              if ( $camp == 1 ) print " colspan=4"; 
              print ">"; 
              print impoboni( $dato[impoingr] ); 
              print "&nbsp;".Mostrarfecha($dato[fechingr])."&nbsp;".$dato[refeingr]; 
            ?> 
          </td> 
         <? } ?> 
         <? if ( $dato[causboni] ) { ?> 
          <td class=izqform
          <? 
            if( ( $camp == 1 ) || (( $camp == 2 ) && ( $dato[porcboni] > 0 )) ) {
              // En el caso de que sólo estén 2 de los campos, y estos sean porcboni y causboni.
              print " colspan=2"; 
            }
          ?>>Beneficios trib.</td> 
          <td class=derform 
            <? 
              if ( $camp == 2 ) {
                if ( $dato[porcboni] > 0 ) {
                  // En el caso de que sólo estén 2 de los campos, y estos sean porcboni y causboni.
                  print " colspan=3"; 
                } else {
                  print " colspan=2"; 
                }
              }
              if ( $camp == 1 ) print " colspan=4"; 
              if ( $camp == 3 ) print " colspan=2"; 
              print ">"; 
              print $dato[causboni]; 
            ?> 
          </td> 
         <? } ?> 
         <? if ( $dato[porcboni] > 0 ) { ?> 
          <? if ( !$dato[causboni] ) { ?>
            <td class=izqform<? if ( $camp == 1 ) print " colspan=2"; ?>>Beneficios trib.</td> 
          <? } ?> 
          <td class=derform 
            <? 
              if ( $camp == 2 ) {
                if ( !$dato[causboni] ) {
                  // En el caso de que hayan dos de los tres campos, y estos sean porcboni e impoingr
                  print " colspan=2"; 
                }
              }
              if ( $camp == 1 ) print " colspan=4"; 
              print ">"; 
              print $dato[porcboni]."%"; 
            ?> 
          </td> 
         <? } ?> 
         </tr>
      <? } ?> 
      <? 
 
      # Ahora mostramos los elementos tributarios (tabla oiaeelem) 
      $query = "SELECT nume, codielem, nomb, cant 
                FROM   cargoiaeelem INNER JOIN oiaeelem 
                       ON cargoiaeelem.codielem = oiaeelem.codi 
                WHERE  codiayun = '$codiayun' AND 
                       codiconc = '$codiconc' AND 
                       ejer     = '$ejer'     AND 
                       numedocu = '$numedocu'"; 
      // print "<b>La consulta de las parcelas es: $query</b><br>\n"; 
      $dato = sql ($query); 
      if (is_array ($dato)) { 
        ?> 
        <tr><td colspan=6> 
          <table width="100%"> 
            <tr> 
              <td class=izqform colspan=5>Elementos tributarios</td> 
            </tr> 
            <? 
            $contelem = count( $dato );  // Numero total de elementos tributarios a mostrar 
            // Se muestran 3 elementos tributarios en cada linea 
            $cont = 0;  // Contador de elementos ya mostrados 
            while ($elem = each ($dato)) { 
              $elem = $elem[value]; 
              if ( $cont % 3 == 0 ) { 
                // Cada 3 elementos, comienza una nueva fila en la tabla 
                print "<tr>"; 
              } 
              $rest = $contelem - $cont; 
              print "<td class=derform align=center>" . cantboni($elem[cant]) . " \"$elem[nomb]\"</td>\n"; 
              $cont++; // Incremento el numero de elementos ya mostrados 
              if ( $cont % 3 == 0 ) { 
                // Ya se han mostrado 3 en la linea. Se salta de linea 
                print "</tr>"; 
              } 
            } 
            ?> 
          </table> 
        </td></tr> 
        <? 
      } 
      break; 
 
    case 'OIPL': 
      ?> 
      <tr> 
        <td colspan=6 class=izqform> 
          INFORMACIÓN DE LA PLUSVALÍA (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <? 
      $query = "SELECT nombnota, fechescr, prot, fechpres, fechdeve, vari, 
                       sigl, nombviax, nume, letr, esca, plan, puer, 
                       nombinmu, idenloca, refecata, numecarg, caracont, 
                       valocata, tiporedu, valoapli, 
                       cuotadqu, aniotran, porcperi, tipograv, 
                       refeingr, fechingr, impoingr, 
                       imporeca, impointe 
                FROM   cargoipl 
                WHERE  codiayun = '$codiayun' AND 
                       codiconc = '$codiconc' AND 
                       numedocu = '$numedocu' AND 
                       ejer     = '$ejer'"; 
      // print "<b>Consulta</b>: $query\n"; 
      $dato = sql ($query); 
      if (is_array ($dato)) { 
        $dato = each ($dato); 
        $dato = $dato[value]; 
      ?> 
      <tr> 
        <td class='izqform'>Código tributario</td> 
        <td align=center colspan=3 class=derform><? print $codiobje ?></td>
        <td class='izqform'>Devengo</td>  
        <td nowrap class=derform><?print mostrarFecha ($dato[fechdeve])?></td> 
      </tr>     
      <tr> 
        <td class=izqform>Notario</td> 
        <td colspan=3 class=derform><?print $dato[nombnota]?></td> 
        <td class=izqform>Escritura</td> 
        <td nowrap class=derform><?print mostrarFecha ($dato[fechescr])?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Protocolo</td> 
        <td class=derform><?print $dato[prot]?></td> 
        <td class=izqform>Variación</td> 
        <td class=derform><?print $dato[vari]?></td> 
        <td class=izqform>Presentación</td> 
        <td nowrap class=derform><?print mostrarFecha ($dato[fechpres])?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Domicilio tributario</td> 
        <td colspan=5 class=derform> 
          <?print mostrarDireccion ($dato[sigl], 
                                    $dato[nombviax], 
                                    $dato[nume], 
                                    $dato[plan], 
                                    $dato[letr], 
                                    $dato[esca], 
                                    $dato[puer])?> 
        </td> 
      </tr> 
      <? if ($dato[nombinmu]) { ?> 
        <tr> 
          <td class=izqform>Nombre inmueble</td> 
          <td colspan=4 class=derform><?print $dato[nombinmu]?></td> 
          <td class=derform><?print $dato[idenloca]?></td> 
        </tr> 
      <? } ?> 
      <? if ($dato[refecata]) { ?> 
        <tr> 
          <td class=izqform>Referencia catastral</td> 
          <td colspan=2 class=derform><?print "$dato[refecata] $dato[numecarg] $dato[caracont]" ?></td> 
          <td class=izqform>Valor catastral</td> 
          <td colspan=2 class=derform><?print impoboni ($dato[valocata])?></td> 
        </tr> 
      <? } ?> 
      <tr> 
        <td class=izqform>Porcentaje reducción</td> 
        <td colspan=2 class=derform><?print "$dato[tiporedu]%" ?></td> 
        <td class=izqform>Valor aplicado</td> 
        <td colspan=2 class=derform><?print impoboni ($dato[valoapli]) ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Cuota adquisición</td> 
        <td colspan=2 class=derform><?print "$dato[cuotadqu]%" ?></td> 
        <td class=izqform>Años transcurridos</td> 
        <td colspan=2 class=derform><?print $dato[aniotran] ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Porcentaje periodo</td> 
        <td colspan=2 class=derform><?print "$dato[porcperi]%" ?></td> 
        <td class=izqform>Tipo de gravamen</td> 
        <td colspan=2 class=derform><?print "$dato[tipograv]%" ?></td> 
      </tr> 
      <? if ($dato[imporeca] or $dato[impointe]) { ?> 
      <tr> 
        <td class=izqform>Recargo</td> 
        <td colspan=2 class=derform><? print impoboni ($dato[imporeca]) ?></td> 
        <td class=izqform>Interés demora</td> 
        <td colspan=2 class=derform><? print impoboni ($dato[impointe]) ?></td> 
      </tr> 
      <? } ?> 
      <? if ($dato[impoingr] or $dato[refeingr] or 
             $dato[fechingr] != '0001-01-01') { ?> 
      <tr> 
        <td class=izqform>Ingreso a cuenta</td> 
        <td colspan=2 class=derform><?print impoboni ($dato[impoingr]) ?></td> 
        <td class=izqform>Referencia del ingreso</td> 
        <td colspan=2 class=derform><?print Mostrarfecha ($dato[fechingr]) . 
                                            " $dato[refeingr]" ?></td> 
      </tr> 
      <? } ?> 
      <? 
      } else { 
      ?> 
      <tr> 
        <td colspan=2 align=center class=derform>No hay datos adicionales que mostrar</td> 
      </tr> 
      <? 
      } 
      break; 
 
 
    case 'OICI': 
      $query = "SELECT nombviax, nume, letr, esca, plan, puer, nombinmu, idenloca, 
                       fechinic, fechbaja, base, origbase, fechbase, 
                         impoingr, refeingr, fechingr, siglviax, 
                         tipoobra.abre as tipoabre, subxobra.abre as subxabre,  
                         subxobra.nomb as subxnomb, porcboni, causboni
                  FROM   cargoici, siglvias, tipoobra, subxobra 
                  WHERE  cargoici.codiconc = '$codiconc' AND 
                         cargoici.codiayun = '$codiayun' AND 
                         cargoici.ejer     = '$ejer' AND 
                         cargoici.numedocu = '$numedocu' AND 
                         cargoici.codisigl = siglvias.codisigl AND 
                         cargoici.codisubxobra = subxobra.codisubxobra AND 
                         subxobra.codiobra = tipoobra.codiobra"; 
       
      // print "<b>Consulta</b>: $query<br>\n"; 
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value]; 
      ?> 
      <tr> 
        <td colspan=4 class=izqform> 
          INFORMACIÓN DE LA CONSTRUCCIÓN, INSTALACIÓN U OBRA (Código tributario: <? print $descobje ?>) 
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código tributario</td> 
        <td align=center class=derform><? print $codiobje ?></td> 
        <? 
        // Tipo de gravamen 
        $query = "SELECT porc FROM   tari, subxconc, cargoici 
                  WHERE  tari.codisubc         = subxconc.codisubc AND 
                         tari.ejer             = substring( cargoici.fechinic from 0 for 5) AND 
                         subxconc.codiconc     = '$codiconc'       AND 
                         cargoici.codiayun     = '$codiayun'       AND 
                         cargoici.codiconc     = '$codiconc'       AND 
                         cargoici.numedocu     = '$numedocu'       AND 
                         cargoici.ejer         = '$ejer'"; 
        $tariporc = sql ($query); 
        ?> 
        <td class=izqform>Tipo de gravamen</td> 
        <td class=derform><? print $tariporc; ?> %</td> 
      </tr> 
      <tr> 
        <td class=izqform>Descripción C.I.O.</td> 
        <td class=derform colspan = 3><? print "$dato[tipoabre].$dato[subxabre] $dato[subxnomb]"; ?></td> 
      </tr> 
      <tr> 
        <td class=izqform>Domicilio tributario</td> 
        <td class=derform colspan = 3> 
        <? 
          print "$dato[siglviax] $dato[nombviax]"; 
          if ( $dato[nume] )  print ", $dato[nume]"; 
          if ( $dato[letr] )  print ", $dato[letr]"; 
          if ( $dato[esca] )  print ",'esc' $dato[esca]"; 
          if ( $dato[plan] )  print ",'planta' $dato[plan]"; 
          if ( $dato[puer] )  print ",'puerta' $dato[puer]"; 
        ?> 
        </td> 
      </tr> 
      <? if ( $dato[nombinmu] ) { ?> 
      <tr> 
        <td class=izqform>Nombre del inmueble</td> 
        <td class=derform colspan = 2><? print $dato[nombinmu]; ?></td> 
        <td class=derform><? print $dato[idenloca]; ?></td> 
      </tr> 
      <? } ?> 
      <tr> 
        <td class=izqform>Fecha inicio</td> 
        <td class=derform><? print Mostrarfecha( $dato[fechinic] ); ?></td> 
        <td class=izqform>Fecha finalización</td> 
        <td class=derform><? print Mostrarfecha( $dato[fechbaja] ); ?></td> 
      </tr> 
      <tr> 
      <td class=izqform>Base imponible</td> 
      <td class=derform><? print impoboni( $dato[base] ); ?></td> 
      <td class=derform> 
        <?  
          switch( $origbase ) { 
            case 'P': print "Visado Colegio Oficial"; 
            case 'E': print "Estimación"; 
            case 'O': print "Técnica"; 
          } 
        ?> 
      </td> 
      <td class=derform><? Mostrarfecha( $dato[fechbase] ); ?></td> 
      </tr> 

      <? if ( $dato[porcboni] || $dato[causboni] ) { ?> 
      <tr> 
        <td class=izqform>Beneficios tributarios</td> 
        <? if ( $dato[causboni] ) {
             print "<td class=derform colspan = 2>".$dato[causboni]."</td>\n";
           }
        ?>
        <td class=derform <? if ( !$dato[causboni] ) print "colspan=3"; ?>>
        <? print $dato[porcboni]."%"; ?>
        </td>
      </tr> 
      <? } ?> 

      <? if ( $dato[impoingr] ) { ?> 
      <tr> 
        <td class=izqform>Ingreso a cuenta</td> 
        <td class=derform><?print impoboni( $dato[impoingr] ) ?></td> 
        <td class=izqform>Referencia del ingreso</td> 
        <td class=derform><?print Mostrarfecha($dato[fechingr])." ".$dato[refeingr] ?></td> 
      </tr> 
      <? } ?> 
      <? 
      break; 
 
    case 'SANCTRAF':
      $query = "SELECT siglvias.siglviax as siglviax, vias.nomb as nombviax, nume, letr, esca, plan, puer,
                       tipoinfr.nombcort as nomborde, infrtraf.arti as arti, infrtraf.apar as apar, 
                       infrtraf.opci as opci, vehitraf.matr as matr, fechdenu, horadenu, numedenu, numeexpe, 
                       anioexpe, porcboni, causboni
                  FROM   ((cargosantraf LEFT JOIN (vias LEFT JOIN siglvias ON vias.codisigl = siglvias.codisigl)
                         ON cargosantraf.codiviax = vias.codiviax)
                         LEFT JOIN vehitraf ON cargosantraf.codivehi = vehitraf.codivehi)
                         LEFT JOIN (infrtraf LEFT JOIN tipoinfr ON infrtraf.coditipo = tipoinfr.coditipo) ON cargosantraf.codiinfr = infrtraf.codiinfr
                  WHERE  cargosantraf.codiconc = '$codiconc' AND
                         cargosantraf.codiayun = '$codiayun' AND
                         cargosantraf.ejer     = '$ejer' AND
                         cargosantraf.numedocu = '$numedocu'";
       
      # print "<b>Consulta</b>: $query<br>\n";
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value]; 
      ?> 
      <tr> 
        <td colspan=4 class=izqform> 
          INFORMACIÓN DE LA DENUNCIA (Código : <? print $descobje ?>)
        </td> 
      </tr> 
      <tr> 
        <td class=izqform>Código</td>
        <td align=center class=derform><? print "$dato[numedenu]" ?></td>
      </tr>
      <tr> 
        <td class=izqform>Expediente</td>
        <td class=derform colspan = 3><? print "$dato[numeexpe]/$dato[anioexpe]"; ?></td>
      </tr> 
      <tr> 
        <td class=izqform>Precepto/Artículo</td>
        <td class=derform colspan = 3><? print "$dato[nomborde] $dato[arti].$dato[apar] $dato[opci]"; ?></td>
      </tr> 
      <tr> 
        <td class=izqform>Matrícula del vehículo</td>
        <td class=derform colspan = 3><? print "$dato[matr]"; ?></td>
      </tr> 
      <tr> 
        <td class=izqform>Fecha/Hora</td>
        <td class=derform colspan = 3><? print mostrarFecha($dato[fechdenu]). " $dato[horadenu]"; ?></td>
      </tr> 
      <tr> 
        <td class=izqform>Lugar de la infracción</td>
        <td class=derform colspan = 3> 
        <? 
          print "$dato[siglviax] $dato[nombviax]"; 
          if ( $dato[nume] )  print ", $dato[nume]"; 
          if ( $dato[letr] )  print ", $dato[letr]"; 
          if ( $dato[esca] )  print ",'esc' $dato[esca]"; 
          if ( $dato[plan] )  print ",'planta' $dato[plan]"; 
          if ( $dato[puer] )  print ",'puerta' $dato[puer]"; 
        ?> 
        </td> 
      </tr> 

      <? if ( $dato[porcboni] || $dato[causboni] ) { ?> 
      <tr> 
        <td class=izqform>Beneficios tributarios</td> 
        <td class=derform >
        <? if ( $dato[causboni] ) {
             print $dato[causboni];
           }
           if ( $dato[porcboni] ) {
             print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$dato[porcboni]."%";
           }
        ?>
      </tr> 
      <? } ?> 

      <?
      break; 

     case 'SANCOTRA':
      $query = "SELECT refeexpe, orgaacue, fechacue, numedenu, codisigl, nombviax,
                nume, letr, esca, plan, puer, nombinmu, idenloca,
                porcboni, impoingr, refeingr, fechingr, causboni
                FROM cargosanotra WHERE  cargosanotra.codiconc = '$codiconc' AND
                cargosanotra.codiayun = '$codiayun' AND
                cargosanotra.ejer     = '$ejer'     AND
                cargosanotra.numedocu = '$numedocu'";
      // print "<b>Consulta SANCOTRA</b>: $query<br>\n";
      $dato = sql ($query);
      ?>
      <tr>
        <td colspan=4 class=izqform>
          INFORMACIÓN DE LA SANCIÓN (Código tributario: <? print $descobje ?>)
        </td>
      </tr>
      <?
      if (is_array ($dato)) {
        $dato = each ($dato);
        $dato = $dato[value];
        $tiposanc = sql ("SELECT nomb FROM conctrib WHERE codiayun = '$codiayun' AND codiconc = '$codiconc'");
        $tipoorga = sql ("SELECT nomb FROM tipoorga WHERE codiorga = '$dato[orgaacue]'");
	$siglviax = sql ("SELECT siglviax FROM siglvias WHERE codisigl = '$dato[codisigl]'");
        ?>
        <tr>
          <td class=izqform>Tipo de Sanción</td>
          <td class=derform colspan=3 align=center>
            <? print $tiposanc ?>
          </td>
        </tr>
        <tr>
          <td class=izqform>Código tributario</td>
          <td align=center class=derform>
            <? print $codiobje ?>
          </td>
            <td class=izqform>Referencia</td>
            <td class=derform><? print $dato[refeexpe]; ?></td>
        </tr>
        <tr>
          <td class=izqform>Acuerdo resolución</td>
          <td align=center class=derform>
            <? print $tipoorga." ". Mostrarfecha($dato[fechacue]) ?>
          </td>
            <td class=izqform>Nº denuncia</td>
            <td class=derform><? print $dato[numedenu]; ?></td>
        </tr>
          <? if ( $dato[nombviax] ) { ?>
          <tr>
            <td class=izqform>Lugar infracción</td>
            <td class=derform colspan=3><? print mostrarDireccion ($siglviax, $dato[nombviax], $dato[nume], $dato[plan], $dato[letr], $dato[esca], $dato[puer]); ?></td>
          </tr>
        <? } ?>
        <? if ( $dato[nombinmu] || $dato[idenloca] ) { ?>
          <tr>
            <td class=izqform>Inmueble</td>
            <? print "<td class=derform>".$dato[nombinmu]."</td>\n"; ?>
            <td class=derform colspan=2>
              <? print $dato[idenloca]; ?>
            </td>
          </tr>
        <? } ?>
        <? if ( $dato[porcboni] || $dato[causboni] ) { ?>
        <tr>
          <td class=izqform>Beneficios tributarios</td>
          <? if ( $dato[causboni] ) {
               print "<td class=derform colspan = 2>".$dato[causboni]."</td>\n";
             }
          ?>
          <td class=derform <? if ( !$dato[causboni] ) print "colspan=3"; ?>>
          <? print $dato[porcboni]."%"; ?>
          </td>
        </tr>
        <? } ?>
        <? if ( $dato[impoingr] ) { ?>
        <tr>
          <td class=izqform>Ingreso a cuenta</td>
          <td class=derform><?print impoboni( $dato[impoingr] ) ?></td>
          <td class=izqform>Referencia del ingreso</td>
          <td class=derform><?print Mostrarfecha($dato[fechingr])." ".$dato[refeingr] ?></td>
        </tr>
        <? } 
      }
      break;


    
    case 'estacont': 
      $query = "SELECT datocont, numedescargo, enti, sucu, ccon, cuen, 
                       domibanc, refeno60, idenno60, 
                       fechcont, horacont, 
                       oficmuni.nomb as oficmuninomb, 
                       banc.nomb as bancnomb, 
                       usua.nombcomp as usuanombcomp,
                       estanotivolu, fechnotivolu, fechinicvolu, fechfinavolu,
                       estanotiprov, fechnotiprov, fechinicprov, fechfinaprov, 
                       numecarg, estacont,
                       estacont.nomb as estacontnomb
                FROM   (((carg LEFT JOIN banc on carg.enti = banc.abre)
                         LEFT JOIN oficmuni 
                         ON carg.codiofic = oficmuni.codiofic) 
                        LEFT JOIN estacont 
                        ON estacont.codiestacont = carg.estacont)
                       LEFT JOIN usua 
                       ON carg.usuacont = usua.codiusua 
                WHERE  carg.codiconc = '$codiconc' AND 
                       carg.codiayun = '$codiayun' AND 
                       carg.ejer     = '$ejer' AND 
                       carg.numedocu = '$numedocu'"; 
      # print "<b>Consulta estacont</b>: $query<br>\n";
      $dato = sql ($query); 
      if (is_array ($dato)) { 
        $dato = each ($dato); 
        $dato = $dato[value]; 
      ?> 
      <tr> 
        <td colspan=2 class=izqform>INFORMACIÓN DEL ESTADO CONTABLE</td> 
      </tr> 


      <tr>
        <td class=izqform>Estado</td>
        <td class=derform><?print $dato[estacontnomb]?></td>
      </tr>

      <? if ($dato[datocont]!='') { ?>
      <tr> 
        <td class=izqform>Datos complementarios</td> 
          <td class=derform><?print $dato[datocont] ?></td>
      </tr> 
      <? } ?>

      <? if ($dato[oficmuninomb]) { ?>
      <tr>
        <td class=izqform>Oficina municipal ingreso</td>
        <td class=derform><?print $dato[oficmuninomb]?></td>
      </tr>
      <? } ?>
        <? if ($dato[enti]) { ?>
      <tr>
        <td class=izqform>Datos bancarios ingreso</td>
        <?
          $ccenti = "";
          if ($dato[enti] != ''){
             $ccenti .= $dato[enti];
          }
          if ($dato[sucu] != ''){
             $ccenti .= "-" . $dato[sucu];
          }
          if ($dato[ccon] != ''){
             $ccenti .= "-" . $dato[ccon];
          }
          if ($dato[cuen] != ''){
             $ccenti .= "-" . $dato[cuen];
          }
        ?>
        <td class=derform>
        <? if (( $dato[domibanc] == 1 ) && 
               ( $dato[estacont]=='IBD' || $dato[estacont]=='IBR' || $dato[estacont]=='PEN' )) { 
             print "DOMICILIACIÓN BANCARIA<br>";
           }
           print ($dato[bancnomb] ? "$dato[bancnomb]<br>" : "") . "$ccenti"?>
        <br><? if ($dato[refeno60] != '') { ?>
        Referencia: <? print $dato[refeno60]; ?>
        <?}?>
        <br><? if ($dato[idenno60] != '') { ?>
        Identificación: <? print $dato[idenno60]; ?>
        <?}?>
        </td>
      </tr>
        <? }
           if ($dato[usuanombcomp]!='')
             echo "<tr><td class=izqform>Usuario registro</td><td class=derform>$dato[usuanombcomp]</td></tr>";
           if ($dato[fechcont] != '0001-01-01') { ?>
        <tr>
          <td class=izqform>Fecha y hora registro</td>
          <td class=derform><?print mostrarFecha ($dato[fechcont]) . " a las $dato[horacont]"?></td>
        </tr>
      <? } ?>

      <? if ($dato[numecarg]) { ?> 
      <tr> 
        <td class=izqform>Nº cargo</td> 
        <td class=derform><?print $dato[numecarg]?></td> 
      </tr> 
      <? } ?> 
      <? if ($dato[numedescargo]) { ?> 
      <tr> 
        <td class=izqform>Nº descargo</td> 
        <td class=derform><?print $dato[numedescargo]?></td> 
      </tr> 
      <? } ?> 

        <tr> 
          <td class=izqform colspan=2>Notificación en voluntaria</td> 
        </tr> 
        <tr> 
          <td class=izqform>Estado</td> 
          <td class=derform><?print sql("SELECT abre FROM estanoti WHERE codiestanoti = '$dato[estanotivolu]'"); ?></td> 
        </tr> 
        <? if ( $dato[fechnotivolu] != '0001-01-01' ) { ?>
        <tr> 
          <td class=izqform>Fecha</td> 
          <td class=derform><?print Mostrarfecha( $dato[fechnotivolu] ); ?></td> 
        </tr> 
        <? } ?>
      <? if ($dato[fechinicvolu] != '0001-01-01') { ?>
        <tr>
          <td class=izqform>Plazo ingreso</td>
          <td class=derform><?print "Inicio: ".Mostrarfecha( $dato[fechinicvolu] )."<br>Final: &nbsp;".Mostrarfecha( $dato[fechfinavolu] ); ?></td>
        </tr>
      <? } ?>
      
        <tr> 
          <td class=izqform colspan=2>Notificación de la providencia de apremio</td> 
        </tr> 
        <tr> 
          <td class=izqform>Estado</td> 
          <td class=derform><?print sql("SELECT abre FROM estanoti WHERE codiestanoti = '$dato[estanotiprov]'"); ?></td> 
        </tr> 
        <? if ( $dato[fechnotiprov] != '0001-01-01' ) { ?>
        <tr> 
          <td class=izqform>Fecha</td> 
          <td class=derform><?print Mostrarfecha( $dato[fechnotiprov] ); ?></td> 
        </tr> 
        <? } ?>
        <? if ($dato[fechinicprov] != '0001-01-01') { ?>
          <tr>
            <td class=izqform>Plazo ingreso</td>
            <td class=derform><?print "Inicio: ".Mostrarfecha( $dato[fechinicprov] )."<br>Final: &nbsp;".Mostrarfecha( $dato[fechfinaprov] ); ?></td>
          </tr>
        <? } ?> 

      <? 
      } else { 
      ?> 
      <tr> 
        <td colspan=2 class=izqform>INFORMACIÓN DEL ESTADO CONTABLE</td> 
      </tr> 
      <tr> 
        <td colspan=2 align=center class=derform>No hay datos adicionales que mostrar</td> 
      </tr> 
      <? 
      } 
      break; 
 

    case 'ingracue':
      $query = "SELECT ingracue.datoingr, 
                       ingracue.enti, ingracue.sucu, ingracue.ccon, ingracue.cuen,
                       ingracue.estacont,
                       ingracue.fechingr,
                       oficmuni.nomb as oficmuninomb,
                       banc.nomb as bancnomb,
                       usua.nombcomp as usuanombcomp,
                       estacont.nomb as estacontnomb
                FROM   (((ingracue LEFT JOIN estacont 
                          ON estacont.codiestacont = ingracue.estacont)
                         LEFT JOIN usua
                         ON ingracue.usuamodi = usua.codiusua)
                        LEFT JOIN oficmuni ON ingracue.codiofic = oficmuni.codiofic) 
                       LEFT JOIN banc ON ingracue.enti = banc.abre
                WHERE ingracue.codiayun = '$codiayun' 
                  AND ingracue.codiconc = '$codiconc' 
                  AND ingracue.ejer = '$ejer' 
                  AND ingracue.numedocu = '$numedocu' 
                  AND ingracue.nume = '$numeingr'";

      # print "<b>Consulta estacont</b>: $query<br>\n";
      $dato = sql ($query);
      if (is_array ($dato)) {
        $dato = each ($dato);
        $dato = $dato[value];
      ?>
      <tr>
        <td colspan=2 class=izqform>INFORMACIÓN DEL INGRESO A CUENTA</td>
      </tr>

      <tr>
        <td class=izqform>Estado</td>
        <td class=derform><?print $dato[estacontnomb]?></td>
      </tr>

      <? if ($dato[datoingr]!='') { ?>
      <tr>
        <td class=izqform>Datos complementarios</td>
          <td class=derform><?print $dato[datoingr] ?></td>
      </tr>
      <? } ?>

      <? if ($dato[oficmuninomb]) { ?>
      <tr>
        <td class=izqform>Oficina municipal ingreso</td>
        <td class=derform><?print $dato[oficmuninomb]?></td>
      </tr>
      <? } ?>
      
      <? if ($dato[enti]) { ?>
      <tr>
        <td class=izqform>Datos bancarios ingreso</td>
         <?
           $ccenti = "";
           if ($dato[enti] != ''){
             $ccenti .= $dato[enti];
           }
           if ($dato[sucu] != ''){
             $ccenti .= "-" . $dato[sucu];
           }
           if ($dato[ccon] != ''){
             $ccenti .= "-" . $dato[ccon];
           }
           if ($dato[cuen] != ''){
             $ccenti .= "-" . $dato[cuen];
           }
        ?>
        <td class=derform>
        <? print ($dato[bancnomb] ? "$dato[bancnomb]<br>" : "") . "$ccenti"?>
        </td>
      </tr>
      <? }
         if ($dato[usuanombcomp]!='')
           echo "<tr><td class=izqform>Usuario registro</td><td class=derform>$dato[usuanombcomp]</td></tr>";
      ?>
       <tr>
         <td class=izqform>Fecha registro</td>
         <td class=derform><?print mostrarFecha ($dato[fechingr])?></td>
       </tr>
      <? 
      } else {
      ?>
      <tr>
        <td colspan=2 class=izqform>INFORMACIÓN DEL INGRESO A CUENTA</td>
      </tr>
      <tr>
        <td colspan=2 align=center class=derform>No hay datos adicionales que mostrar</td>
      </tr>
      <?
      }
      break;


    default: 
      print "<script>alert ('ERROR: No existe el tipo de objeto \"$tipoobje\"'); window.close ()</script>\n"; 
      exit; 
  } 
?> 
