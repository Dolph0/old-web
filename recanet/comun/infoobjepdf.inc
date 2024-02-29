<?
  //include_once "comun/cantboni.fnc";
  include_once "comun/dire.fnc";

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
                       porcboni, impoingr, refeingr, fechingr, impointe 
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
      $header=array("INFORMACIÓN DE VEHICULOS (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Código tributario','Bastidor');
      $data=array($codiobje,$dato[bast]);
      $ancho=array(30, 76, 25, 42);
      $tipo=array('C','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Marca-modelo','Tipo vehículo');
      $data=array($dato[marcmode],$dato[tipo]);
      $ancho=array(30, 76, 25, 42);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Servicio','Plazas');
      $data=array($dato[serv],$dato[plaz]);
      $ancho=array(30, 76, 25, 42);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Carga','Cilindrada');
      $data=array($dato[capa] . ' kg',$dato[cili] . ' cc');
      $ancho=array(30, 76, 25, 42);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Potencia','Categoría');
      $data=array($dato[pote] . ' hp',$dato[calc]);
      $ancho=array(30, 76, 25, 42);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[porcboni] ) {
         $header=array('Beneficios tributarios');
         $data=array(array($dato[porcboni]."%"));
         $ancho=array(30,143);
         $tipo=array('L');
         Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
         if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      if ( $dato[impoingr] ) {
         $header=array('Ingreso a cuenta', 'Referencia del ingreso');
         $data=array(array(impobonipdf( $dato[impoingr] ), Mostrarfecha($dato[fechingr])." ".$dato[refeingr]));
         $ancho=array(30, 76, 25, 42);
         $tipo=array('L','L');
         Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
         if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
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
                       cargoibiurba.numecarg as cargoibiurbanumecarg, 
                       cargoibiurba.caracont as cargoibiurbacaracont, 
                       cargoibiurba.impoingr as cargoibiurbaimpoingr, 
                       cargoibiurba.fechingr as cargoibiurbafechingr, 
                       cargoibiurba.refeingr as cargoibiurbarefeingr, 
                       cargoibiurba.impointe as cargoibiurbaimpointe 
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
      $header=array("INFORMACIÓN DE LA FINCA URBANA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Código tributario','Tipo gravamen');
      $data=array($codiobje . " Cargo " .  $dato[cargoibiurbanumecarg] . " C. Control " . $dato[cargoibiurbacaracont],$tariporc . " %");
      $ancho=array(35, 96, 25, 17);
      $tipo=array('C','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Domicilio tributario');
      $datodire = mostrarDireccion ($dato[siglviassiglviax],
                                    $dato[viasnomb],
                                    $dato[cargoibiurbanume],
                                    $dato[cargoibiurbaplan],
                                    $dato[cargoibiurbaletr],
                                    $dato[cargoibiurbaesca],
                                    $dato[cargoibiurbapuer]);
      $data=array($datodire);
      $ancho=array(35, 138);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ($dato[cargoibiurbanomb]) {
         $header=array('Nombre del inmueble');
         $data=array($dato[cargoibiurbanomb] . ($dato[cargoibiurbaidenloca]?$dato[cargoibiurbaidenloca]:''));
         $ancho=array(35, 138);
         $tipo=array('L');
         Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
         if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      if ( ( $dato[cargoibiurbavalosuel] ) || ( $dato[cargoibiurbavalocons] ) ) {
         $header=array('Valor suelo','Valor construcción');
         $data=array(impobonipdf ($dato[cargoibiurbavalosuel]),impobonipdf ($dato[cargoibiurbavalocons]));
         $ancho=array(35, 51, 35, 52);
         $tipo=array('L','L');
         Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
         if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      $header=array('Valor catastral','Base liquidable');
      $data=array(impobonipdf ($dato[cargoibiurbavalocata]),impobonipdf ($dato[cargoibiurbabase]));
      $ancho=array(35, 51, 35, 52);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[cargoibiurbaporcboni] || $dato[cargoibiurbaimpointe] ) {
         if ( $dato[cargoibiurbaporcboni] ) {
            if ( $dato[cargoibiurbaimpointe] ) {
                $header=array('Beneficios tributarios','Interés de demora');
                $data=array($dato[cargoibiurbaporcboni]."%",impobonipdf( $dato[cargoibiurbaimpointe] ));
                $ancho=array(35, 51, 35, 52);
                $tipo=array('L','L');
                Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
                if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
            }
         }else{
            $header=array('Beneficios tributarios');
            $data=array($dato[cargoibiurbaporcboni]."%");
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
         }
      } else if ($dato[cargoibiurbaporcboni] ) {
            $header=array('Interés de demora');
            $data=array(impobonipdf( $dato[cargoibiurbaimpointe] ));
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      if ( $dato[cargoibiurbaimpoingr] ) {
          $header=array('Ingreso a cuenta','Referencia del ingreso');
          $data=array(impobonipdf ($dato[cargoibiurbaimpoingr]),Mostrarfecha($dato[cargoibiurbafechingr]) . " " . $dato[cargoibiurbarefeingr]);
          $ancho=array(35, 51, 35, 52);
          $tipo=array('L','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      $reviurba = sql ("SELECT reviurba FROM ayun WHERE codiayun = '$codiayun'");
      if ($reviurba != '') {
          $header=array("Municipio revisado por el Catastro en $reviurba ");
          $data=array('');
          $ancho=array(173,0);
          $tipo=array();
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      break;


    case 'OIBIRUST':
      // Para la funcion supeboni()
      include_once "comun/supe.fnc";
 
      $query = "SELECT coefpart, porcboni, impoingr, fechingr, refeingr, impointe
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
      $header=array("INFORMACIÓN DE LA FINCA RÚSTICA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Código tributario');
      $data=array($codiobje);
      $ancho=array(35, 138);
      $tipo=array('C');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Superficie total/titular','Valor catastral total/titular');
      $data=array(supeboni($datoparc[supe]),impobonipdf ($datoparc[valocata]));
      $ancho=array(35, 51, 42, 45);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $tariporc > 0 ) {
        $header=array('Tipo de gravamen','Coef. participación');
        $data=array($tariporc." %",$dato[coefpart]." %");
        $ancho=array(35, 51, 42, 45);
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      if ( $dato[porcboni] || $dato[impointe] ) {
        if ( $dato[porcboni] ) {
          if ( $dato[impointe] ) {
            $header=array('Beneficios tributarios','Interés de demora');
            $data=array($dato[porcboni]."%",impobonipdf( $dato[impointe] ));
            $ancho=array(35, 51, 42, 45);
            $tipo=array('L','L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          }else{
            $header=array('Beneficios tributarios');
            $data=array($dato[porcboni]."%");
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          }
        } else if ( $dato[impointe] ) {
            $header=array('Interés de demora');
            $data=array(impobonipdf( $dato[impointe] ));
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      if ( $dato[impoingr] ) {
          $header=array('Ingreso a cuenta','Referencia del ingreso');
          $data=array(impobonipdf ($dato[impoingr]),Mostrarfecha($dato[fechingr]) . " " . $dato[refeingr]);
          $ancho=array(35, 51, 42, 45);
          $tipo=array('L','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }

/*      # Ahora la consulta para las diferentes parcelas (tabla cargoibirustparc)
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
      }*/
      break;


    case 'OTPP': 
      $query = "SELECT siglviax, vias.nomb as nomb, refetasa, nume,  
                       letr, esca, plan, puer, datoactu, datoante, 
                       porcboni, impoingr, refeingr, fechingr, impointe 
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
      $header=array("INFORMACIÓN DE TASAS Y PRECIOS PÚBLICOS (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (is_array ($dato)) {
        $dato = each ($dato);
        $dato = $dato[value];
        if ( $dato[refetasa] ) {
              $header=array('Código tributario','Referencia');
              $data=array($codiobje,$dato[refetasa]);
              $ancho=array(35, 51, 35, 52);
              $tipo=array('C','L');
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }else{
              $header=array('Código tributario');
              $data=array($codiobje);
              $ancho=array(35, 138);
              $tipo=array('C');
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[nomb]) {
          $datodire = mostrarDireccion ($dato[siglviax], $dato[nomb], $dato[nume], $dato[plan], $dato[letr], $dato[esca], $dato[puer]);
          $header=array('Domicilio tributario');
          $data=array($datodire);
          $ancho=array(35, 138);
          $tipo=array('L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[datoactu] || $dato[datoante]) {
          $header=array('Lectura actual','Lectura anterior');
          $data=array($dato[datoactu],$dato[datoante]);
          $ancho=array(35, 51, 35, 52);
          $tipo=array('C','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ( $dato[porcboni] || $dato[impointe] ) {
          if ( $dato[porcboni] ) {
            if ( $dato[impointe] ) {
              $header=array('Beneficios tributarios','Interés de demora');
              $data=array($dato[porcboni]."%",impobonipdf( $dato[impointe] ));
              $ancho=array(35, 51, 42, 45);
              $tipo=array('L','L');
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
            }else{
              $header=array('Beneficios tributarios');
              $data=array($dato[porcboni]."%");
              $ancho=array(35, 138);
              $tipo=array('L');
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
            }
          } else if ( $dato[impointe] ) {
              $header=array('Interés de demora');
              $data=array(impobonipdf( $dato[impointe] ));
              $ancho=array(35, 138);
              $tipo=array('L');
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          }
        }
        if ( $dato[impoingr] ) {
            $header=array('Ingreso a cuenta','Referencia del ingreso');
            $data=array(impobonipdf ($dato[impoingr]),Mostrarfecha($dato[fechingr]) . " " . $dato[refeingr]);
            $ancho=array(35, 51, 42, 45);
            $tipo=array('L','L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        //Subconceptos
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
          $data = array();
          while ($valo = each ($dato)) {
            $valo = $valo[value];
            $reg = sql ("SELECT nomb FROM tari WHERE cate = '$valo[busc]' AND codisubc='$valo[codisubc]'");
            if (!empty($reg)){
               if (is_array ($reg)) {
                  $val = each ($reg);
                  $val = $val[value];
                  $val = $val[nomb];
               }else $val=$reg;
               $row = array($val, $valo[oper]);
               array_push($data, $row);
            }
          }
          if (count($data) > 0){
            $header=array('Subconcepto','Operador');
            $ancho=array(121,52);
            $tipo=array('C','C');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto);
          }
        }
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
      $header=array("INFORMACIÓN DE LA ACTIVIDAD ECONÓMICA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      switch ( $dato[periextr] ) {
            case '0A': $datoperi = "ANUAL"; break;
            case '1T': $datoperi = "1TRIMESTRE"; break;
            case '2T': $datoperi = "2TRIMESTRE"; break;
            case '3T': $datoperi = "3TRIMESTRE"; break;
            case '4T': $datoperi = "4TRIMESTRE"; break;
      }
      $header=array('Código tributario','Tipo', 'Extracción');
      $data=array($codiobje,$dato[tipoacti],$datoperi);
      $ancho=array(35, 30, 15, 30, 35, 28);
      $tipo=array('C','L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Domicilio','I.Neto Cifra Negocio');
      $datodire = mostrarDireccion ($dato[sigl],
                                    $dato[nomb],
                                    $dato[nume],
                                    $dato[plan],
                                    $dato[letr],
                                    $dato[esca],
                                    $dato[puer]);
      $data=array($datodire,$dato[impoincn] ? $dato[impoincn]." &euro;":"SIN DECLARAR" );
      $ancho=array(35, 75, 35, 28);
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[tipooper] == "A" ) {
        $cabe = "Fecha variación";
      } elseif ( $dato[tipooper] == "B" ) {
        $cabe = "Fecha cese";
      } else {
        $cabe = "Fecha variación/cese";
      }
      $header=array('Índice de situación','Fecha inicio', $cabe);
      $data=array($dato[indisitu], Mostrarfecha($dato[fechinic]), Mostrarfecha($dato[fechcese]));
      $ancho=array(35, 30, 25, 20, 35, 28);
      $tipo=array('L','L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Grupo o epígrafe');
      $data=array("$dato[tari] $dato[codigrup] $dato[descepig]");
      $ancho=array(35, 138);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
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
      $header=array('Cuota mínima',strtolower($vectsubc[0][nomb]), strtolower($vectsubc[1][nomb]));
      $data=array(impobonipdf($dato[cuottari]), $vectsubc[0][porc]."%", $vectsubc[1][porc]."%");
      $ancho=array(35, 30, 30, 15, 48, 15);
      $tipo=array('L','L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ($dato[supetota] || $dato[superect] || $dato[supecomp]) {
        $header=array('Sup. Total|Rectificada|Computable');
        $data=array("$dato[supetota]|$dato[superect]|$dato[supecomp]");
        $ancho=array(65, 108);
        $tipo=array('L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      if ($dato[nombcome]){
        $header=array('Nombre');
        $data=array($dato[nombcome]);
        $ancho=array(65, 108);
        $tipo=array('L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      // Busco la cuota provincial y municipal que están en la tabla cargdeud
      $conssubc = "SELECT nomb, subxdeud FROM cargdeud CD INNER JOIN subxconc SC
                   ON CD.codisubc = SC.codisubc WHERE codiayun = '$codiayun' AND
                   CD.codiconc = '$codiconc' AND ejer = '$ejer' AND numedocu = '$numedocu'";
      $resusubc = sql( $conssubc );
      if ( $resusubc ) {
        $nombsubc = "";
        $valosubc = "";
        $header=array();
        $data=array();
        while ( $subcvalo = each( $resusubc ) ) {
          $subcvalo = $subcvalo[value];
          array_push($header, $subcvalo[nomb]);
          array_push($data, impobonipdf($subcvalo[subxdeud]));
        }
        $ancho=array(35, 51, 35, 52);
        $tipo=array('L','L');
        if (count($header) > 0){
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      if ( $dato[impointe] || $dato[impoingr] || ( $dato[porcboni] > 0 ) ) {
        $header=array();
        $data=array();
        $ancho=array();
        $tipo=array();
        $width = 0;
        $pos = array(0,0,0);
        if ($dato[porcboni]){
           $pos[0] = 1;
        }
        if ($dato[impointe]){
           $pos[1] = 1;
        }
        if ($dato[impoingr]){
           $pos[2] = 1;
        }
        if ($dato[porcboni]){
          array_push($header, 'Beneficios trib.');
          array_push($data, $dato[porcboni]."%");
          array_push($ancho, 35);
          if ($pos[1] + $pos[2] > 0)
                array_push($ancho, 18);
          else
                array_push($ancho, 173 - 35);
          array_push($tipo, 'L');
          $width += 35 + 18;
        }
        if ($dato[impointe]){
          array_push($header, 'Beneficios trib.');
          array_push($data, impobonipdf( $dato[impointe] ));
          array_push($ancho, 35);
          if ($pos[2] > 0)
                array_push($ancho, 18);
          else
                array_push($ancho, 173 - width - 35);
          array_push($tipo, 'L');
          $width += 35 + 18;
        }
        if ($dato[impoingr]){
          array_push($header, 'Ingreso a cuenta');
          array_push($data, impobonipdf( $dato[impoingr]) . " " . Mostrarfecha($dato[fechingr])." ".$dato[refeingr] );
          array_push($ancho, 35);
          array_push($ancho, 173 - $width + 35);
          array_push($tipo, 'L');
        }
        if (count($header) > 0){
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      $cont = sql("SELECT MAX(nume) FROM cargoiaeelem WHERE  codiayun = '$codiayun' AND
                       codiconc = '$codiconc' AND
                       ejer     = '$ejer'     AND
                       numedocu = '$numedocu'");
      # Ahora mostramos los elementos tributarios (tabla oiaeelem)
      # si son más de 3 no se muestran
      if ($cont && $cont <= 3){
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
          $header=array("Elementos tributarios");
          $data=array('');
          $ancho=array(173,0);
          $tipo=array();
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          $contelem = count( $dato );  // Numero total de elementos tributarios a mostrar
          // Se muestran 3 elementos tributarios en cada linea
          $cont = 0;  // Contador de elementos ya mostrados
          $header=array();
          $data=array();
          $ancho=array();
          $tipo=array();
          while ($elem = each ($dato)) {
            $elem = $elem[value];
            if ( $cont % 3 == 0 && $cont > 0) {
              // Cada 3 elementos, comienza una nueva fila en la tabla
              Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
              if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
              $header=array();
              $data=array();
              $ancho=array();
              $tipo=array();
            }
            array_push($header, $elem[nomb]);
            array_push($data, $elem[cant]);
            array_push($ancho, 45,15);
            array_push($tipo, 'L');
            $rest = $contelem - $cont;
            $cont++; // Incremento el numero de elementos ya mostrados
          }
          $width = 0;
          for ($k = 0; $k < count($ancho) - 1; $k++){
              $width += $ancho[$k];
          }
          $ancho[count($ancho) - 1] = 173 - $width;
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      break;

    case 'OIPL':
      $header=array("INFORMACIÓN DE LA PLUSVALÍA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
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
        $header=array('Código tributario','Devengo');
        $ancho=array(35, 75, 35, 28);
        $data=array($codiobje, mostrarFecha ($dato[fechdeve]));
        $tipo=array('C','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Notario','Escritura');
        $ancho=array(35, 75, 35, 28);
        $data=array($dato[nombnota], mostrarFecha ($dato[fechescr]));
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Protocolo','Variación', 'Presentación');
        $data=array($dato[prot], $dato[vari], mostrarFecha ($dato[fechpres]));
        $ancho=array(35, 30, 25, 20, 35, 28);
        $tipo=array('L','L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Domicilio tributario');
        $datodire = mostrarDireccion ($dato[sigl],
                                      $dato[nombviax],
                                      $dato[nume],
                                      $dato[plan],
                                      $dato[letr],
                                      $dato[esca],
                                      $dato[puer]);
        $data=array($datodire);
        $ancho=array(35, 138);
        $tipo=array('L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if ($dato[nombinmu]) {
          $header=array('Nombre inmueble');
          $data=array($dato[nombinmu] . " " . $dato[idenloca]);
          $ancho=array(35, 138);
          $tipo=array('L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[refecata]) {
          $header=array('Referencia catastral','Valor catastral');
          $ancho=array(35, 51, 42, 45);
          $data=array("$dato[refecata] $dato[numecarg] $dato[caracont]", impobonipdf ($dato[valocata]));
          $tipo=array('L','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        $header=array('Porcentaje reducción','Valor aplicado');
        $ancho=array(35, 51, 42, 45);
        $data=array("$dato[tiporedu]%", impobonipdf ($dato[valoapli]));
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Cuota adquisición','Años transcurridos');
        $ancho=array(35, 51, 42, 45);
        $data=array("$dato[cuotadqu]%", $dato[aniotran]);
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Porcentaje periodo','Tipo de gravamen');
        $ancho=array(35, 51, 42, 45);
        $data=array("$dato[porcperi]%", "$dato[tipograv]%");
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if ($dato[imporeca] or $dato[impointe]) {
          $header=array('Recargo','Interés demora');
          $ancho=array(35, 51, 42, 45);
          $data=array(impobonipdf ($dato[imporeca]) , impobonipdf ($dato[impointe]));
          $tipo=array('L','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[impoingr] or $dato[refeingr] or $dato[fechingr] != '0001-01-01') {
          $header=array('Ingreso a cuenta','Referencia del ingreso');
          $ancho=array(35, 51, 42, 45);
          $data=array(impobonipdf ($dato[impoingr]), Mostrarfecha ($dato[fechingr]) . " $dato[refeingr]");
          $tipo=array('L','L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      break;


    case 'OICI':
      $query = "SELECT nombviax, nume, letr, esca, plan, puer, nombinmu, idenloca,
                       fechinic, fechbaja, base, origbase, fechbase,
                         impoingr, refeingr, fechingr, siglviax,
                         tipoobra.abre as tipoabre, subxobra.abre as subxabre,
                         subxobra.nomb as subxnomb 
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
      $header=array("INFORMACIÓN DE LA CONSTRUCCIÓN, INSTALACIÓN U OBRA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
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
      $header=array('Código tributario','Tipo de gravamen');
      $ancho=array(35, 75, 35, 28);
      $data=array($codiobje, $tariporc);
      $tipo=array('C','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Descripción C.I.O.');
      $ancho=array(35, 138);
      $data=array("$dato[tipoabre].$dato[subxabre] $dato[subxnomb]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Domicilio tributario');
      $ancho=array(35, 138);
      $datodire = mostrarDireccion ($dato[siglviax],
                                    $dato[nombviax],
                                    $dato[nume],
                                    $dato[plan],
                                    $dato[letr],
                                    $dato[esca],
                                    $dato[puer]);
      $data=array($datodire);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[nombinmu] ) {
        $header=array('Nombre del inmueble');
        $ancho=array(35, 138);
        $data=array($dato[nombinmu] . " " . $dato[idenloca]);
        $tipo=array('L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      $header=array('Fecha inicio','Fecha finalización');
      $ancho=array(35, 75, 35, 28);
      $data=array(Mostrarfecha( $dato[fechinic] ), Mostrarfecha( $dato[fechbaja] ));
      $tipo=array('L','L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Base imponible');
      $ancho=array(35, 138);
      switch( $origbase ) {
        case 'P': $aux = "Visado Colegio Oficial";
        case 'E': print "Estimación";
        case 'O': print "Técnica";
      }
      $data=array(impobonipdf( $dato[base] ) . " " . $origbase . " " . Mostrarfecha( $dato[fechbase] ));
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[impoingr] ) {
        $header=array('Ingreso a cuenta','Referencia del ingreso');
        $ancho=array(35, 75, 35, 28);
        $data=array(impobonipdf( $dato[impoingr] ), Mostrarfecha($dato[fechingr])." ".$dato[refeingr]);
        $tipo=array('L','L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      break;

    case 'SANCTRAF':
      $query = "SELECT siglvias.siglviax as siglviax, vias.nomb as nombviax, nume, letr, esca, plan, puer,
                       tipoinfr.nombcort as nomborde, infrtraf.arti as arti, infrtraf.apar as apar, infrtraf.opci as opci,
                       vehitraf.matr as matr, fechdenu, horadenu, numedenu, numeexpe, anioexpe
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
      $header=array("INFORMACIÓN DE LA DENUNCIA (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Código');
      $ancho=array(35, 138);
      $data=array($dato[numedenu]);
      $tipo=array('C');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Expediente');
      $ancho=array(35, 138);
      $data=array("$dato[numeexpe]/$dato[anioexpe]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Precepto/Artículo');
      $ancho=array(35, 138);
      $data=array("$dato[nomborde] $dato[arti].$dato[apar] $dato[opci]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Matrícula del vehículo');
      $ancho=array(35, 138);
      $data=array("$dato[matr]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Fecha/Hora');
      $ancho=array(35, 138);
      $data=array(mostrarFecha($dato[fechdenu]). " $dato[horadenu]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Lugar de la infracción');
      $ancho=array(35, 138);
      $datodire = mostrarDireccion ($dato[siglviax],
                                    $dato[nombviax],
                                    $dato[nume],
                                    $dato[plan],
                                    $dato[letr],
                                    $dato[esca],
                                    $dato[puer]);
      $data=array($datodire);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      break;

     case 'SANCOTRA':
      $query = "SELECT refeexpe, orgaacue, fechacue, numedenu, codisigl, nombviax,
                nume, letr, esca, plan, puer, nombinmu, idenloca,
                porcboni, impoingr, refeingr, fechingr, causboni
                FROM cargosanotra WHERE  cargosanotra.codiconc = '$codiconc' AND
                cargosanotra.codiayun = '$codiayun' AND
                cargosanotra.ejer     = '$ejer'     AND
                cargosanotra.numedocu = '$numedocu'";
      # print "<b>Consulta</b>: $query<br>\n";
      $dato = sql ($query); 
      $dato = each ($dato); 
      $dato = $dato[value];
      $tiposanc = sql ("SELECT nomb FROM conctrib WHERE codiayun = '$codiayun' AND codiconc = '$codiconc'");
      $tipoorga = sql ("SELECT nomb FROM tipoorga WHERE codiorga = '$dato[orgaacue]'");
      $siglviax = sql ("SELECT siglviax FROM siglvias WHERE codisigl = '$dato[codisigl]'");

      $header=array("INFORMACIÓN DE LA SANCIÓN (Código tributario: " . $descobje . ")");
      $data=array('');
      $ancho=array(173,0);
      $tipo=array();
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Tipo de Sanción');
      $ancho=array(35, 138);
      $data=array($tiposanc);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Código Tributario','Referencia');
      $ancho=array(35, 76, 20, 42);
      $data=array("$codiobje","$dato[refeexpe]");
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      $header=array('Acuerdo resolución','Nº denuncia');
      $ancho=array(35, 76, 20, 42);
      $data=array($tipoorga." ". Mostrarfecha($dato[fechacue]),$dato[numedenu]);
      $tipo=array('L');
      Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
      if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      if ( $dato[nombviax] ){
	  $header=array('Lugar de la infracción');
	  $ancho=array(35, 138);
	  $datodire = mostrarDireccion ($dato[siglviax],
		                        $dato[nombviax],
			                $dato[nume],
				        $dato[plan],
					$dato[letr],
	                                $dato[esca],
		                        $dato[puer]);
	  $data=array($datodire);
	  $tipo=array('L');
	  Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
	  if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);	
      }
      if ( $dato[nombinmu] || $dato[idenloca] ) {
	  if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
	  $header=array('Inmueble');
	  $ancho=array(35, 138);
	  $data=array($dato[nombinmu]." ".$dato[idenloca] );
	  $tipo=array('L');
	  Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
	  if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1); 
      }
      if ( $dato[porcboni] || $dato[causboni] ) {
        if ( $dato[porcboni] ) {
          if ( $dato[causboni] ) {
            $header=array('Beneficios tributarios');
            $data=array($dato[porcboni]."%", $dato[causboni] );
            $ancho=array(35, 51, 42, 45);
            $tipo=array('L','L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          }else{
            $header=array('Beneficios tributarios');
            $data=array($dato[porcboni]."%");
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
          }
        } else if ( $dato[causboni] ) {
            $header=array('Beneficios tributarios');
            $data=array($dato[causboni]);
            $ancho=array(35, 138);
            $tipo=array('L');
            Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
            if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
      }
      if ( $dato[impoingr] ) {
         $header=array('Ingreso a cuenta', 'Referencia del ingreso');
         $data=array(array(impobonipdf( $dato[impoingr] ), Mostrarfecha($dato[fechingr])." ".$dato[refeingr]));
         $ancho=array(30, 76, 25, 42);
         $tipo=array('L','L');
         Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
         if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      break;

    case 'estacont': 
      $query = "SELECT datocont, numedescargo, enti, sucu, ccon, cuen, 
                       domibanc, refeno60, idenno60, 
                       fechcont, horacont, 
                       oficmuni.nomb as oficmuninomb, 
                       banc.nomb as bancnomb, 
                       usua.nombcomp as usuanombcomp 
                FROM   ((carg LEFT JOIN banc on carg.enti = banc.abre) 
                        LEFT JOIN oficmuni 
                        ON carg.codiofic = oficmuni.codiofic) 
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
        $header=array("INFORMACIÓN DEL ESTADO CONTABLE (Código tributario: " . $descobje . ")");
        $data=array('');
        $ancho=array(173,0);
        $tipo=array();
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array('Datos complementarios');
        $ancho=array(35, 138);
        $data=array($dato[datocont]!=''? $dato[datocont]:'No hay datos complementarios');
        $tipo=array('L');
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if ($dato[numedescargo]) {
          $header=array('Nº descargo');
          $ancho=array(35, 138);
          $data=array($dato[numedescargo]);
          $tipo=array('L');
          Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
          if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[oficmuninomb]) {
           $header=array('Oficina municipal ingreso');
           $ancho=array(35, 138);
           $data=array($dato[oficmuninomb]);
           $tipo=array('L');
           Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
           if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        }
        if ($dato[enti] && substr($dato[estacont],0,1)=='I') {
           $header=array('Datos bancarios ingreso');
           $ancho=array(35, 138);
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
           if ($dato[refeno60] != '') {
            $ccenti .= " Referencia: $dato[refeno60]";
           }
           if ($dato[idenno60] != '') {
            $ccenti .= " Identificación: $dato[idenno60]";
           }
           $data=array(($dato[bancnomb] ? "$dato[bancnomb]<br>" : "") . "$ccenti");
           $tipo=array('L');
           Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
           if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
       }
       if ($dato[usuanombcomp]!=''){
           $header=array('Usuario registro');
           $ancho=array(35, 138);
           $data=array($dato[usuanombcomp]);
           $tipo=array('L');
           Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
           if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
       }
       if ($dato[fechcont] != '0001-01-01') {
           $header=array('Fecha y hora registro');
           $ancho=array(35, 138);
           $data=array( mostrarFecha ($dato[fechcont]) . " a las $dato[horacont]");
           $tipo=array('L');
           Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto,1);
           if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
       }
      } else {
        $header=array("INFORMACIÓN DEL ESTADO CONTABLE");
        $data=array('');
        $ancho=array(173,0);
        $tipo=array();
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        $header=array("No hay datos adicionales que mostrar");
        $data=array('');
        $ancho=array(173,0);
        $tipo=array();
        Tabla($pdf,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
        if (isset($pdfindi)) Tabla($pdfindi,$ancho,$tipo,$header,$data,$tamano,$alto, 1);
      }
      break; 
 
    default: 
      print "<script>alert ('ERROR: No existe el tipo de objeto \"$tipoobje\"'); window.close ()</script>\n"; 
      exit; 
  } 
?>
