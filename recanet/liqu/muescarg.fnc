
<?
function caberesu() {
// Funcion que imprime la cabecera de la tabla resumen del cargo a generar
  print "<table>\n";

  // Botones para anular seleccion
  print "<tr>\n";
  print "<td colspan=13>";
  print "<div id='botolist' class='solopantalla'>\n";
  echo "<input type='button' value='Anular selección' onClick=\"asig ('carg.cargo', '.checked', 0, contobje.value - 1, '');\">\n
        <input type='button' value='Seleccionar todo' onClick=\"asig ('carg.cargo', '.checked', 0, contobje.value - 1, 'true');\">\n";
  print "</div>\n";
  print "</td>\n";
  print "</tr>\n";
  
  print "<tr>\n";
  print "<td class = \"izqform\" colspan = 7>Cargo que desea generar</td>\n";
  print "<td class = \"izqform\" colspan = 6>Cargo ya existente</td>\n";
  print "</tr>\n";
  print "<tr>\n";
  // Cargo que se desea generar
  print "<td class = \"izqform\"></td>\n";
  print "<td class = \"izqform\">Grupo</td>\n";
  print "<td class = \"izqform\">Concepto</td>\n";
  print "<td class = \"izqform\">Objeto</td>\n";
  print "<td class = \"izqform\">NIF</td>\n";
  print "<td class = \"izqform\">Cuota</td>\n";
  print "<td class = \"izqform\">Deuda</td>\n";

  // Cargo ya existente que hace conflicto con el que se desea generar ahora
  print "<td class = \"izqform\" nowrap>Fecha cargo</td>\n";
  print "<td class = \"izqform\">Per.</td>\n";
  print "<td class = \"izqform\">Estado<br>contable</td>\n";
  print "<td class = \"izqform\">NIF</td>\n";
  print "<td class = \"izqform\">Deuda</td>\n";
  print "<td class = \"izqform\">Suspensión</td>\n";
  print "</tr>\n";
}

function cabecarg($ejer,$anio,$modoliqu,$periliqu) {
// Funcion que imprime la cabecera de la tabla del cargo generado
  print "Fecha del cargo: ".date( 'd-m-Y', time() )."\n<br>\n";
  print "Ejercicio: $ejer\n";
  print "Contraído: $anio\n";
  print "Modalidad: $modoliqu\n";
  print "Periodo: $periliqu\n";
  print "<table width='100%'>\n";
  print "<tr>\n";
  // Cargo que se desea generar
  print "<td class = \"izqform\">Documento</td>\n";
  print "<td class = \"izqform\">Concepto</td>\n";
  print "<td class = \"izqform\">Código Trib.</td>\n";
  print "<td class = \"izqform\">NIF</td>\n";
  print "<td class = \"izqform\">Titular</td>\n";
  print "<td class = \"izqform\">Cuota</td>\n";
  print "<td class = \"izqform\">Deuda</td>\n";
  print "</tr>\n";
}


function resucarg( $dato, $contobje, $codiayun, $anio, $periliqu, $modoliqu ) {
// Esta funcion muestra una linea de la tabla resumen del cargo que se va a generar, es decir, 
// muestra el resumen de los datos de la liquidacion de un objeto segun un concepto.
// $dato es el vector con los datos de la liquidación del objeto que se creó con la función creavect()
// El contador $contobje se usa para crear unos campos ocultos en el
// formulario, que indican si el cargo a generar ya existe 
// $codiayun, $anio y $periliqu los recibe para pasarselos a cheqcarg, que los necesita
// $modoliqu se necesita para saber si es una liquidacion directa o no
  print "<tr>\n";
  print "<td class = \"derform\">";
  
  $nombcheqcargo = "cargo".$contobje;
  print "<div id='cheqcargo$contobje' visibility:visible>\n";
  print "<input type = 'checkbox' name = '$nombcheqcargo' checked>";
  print "</div>\n";
  
  print "</td>\n";
  print "<td class = \"derform\">".$dato[abregrup]."</td>\n";
  print "<td class = \"derform\">".$dato[abreconc]."</td>\n";
  print "<td class = \"derform\">".$dato[abreobje]."</td>\n";
  print "<td class = \"derform\">".$dato[nifx]."</td>\n";
  print "<td class = \"derform\" nowrap>";
  if ( $dato[cuot] == -1 ) { print "<font color='red'>ERROR</font>"; }
  else { print impoboni($dato[cuot]); }
  print "</td>\n";
  print "<td class = \"derform\" nowrap>";
  if ( $dato[deud] == -1 ) { print "<font color='red'>ERROR</font>"; }
  else { print impoboni($dato[deud]); }
  print "</td>\n";


  // Compruebo para cada objeto, si ya fue liquidado anteriormente según
  // los campos codiayun, codiconc, ejer, codiobje, anio y periliqu.
  // Creo un vector que contenga los datos del cargo que ya
  // existia para ese objeto
  $resu = cheqcarg( $dato, $codiayun, $anio, $periliqu );

  // Contador de cargos ya existentes para ese objeto en ese año y periodo
  $i = 0;  
  if ( is_array( $resu ) ) {

    // Tipo de los cargos ya existentes. Si uno de ellos es un ingreso, el tipo es I. 
    // Si no hay ningun ingreso y alguno esta pendiente de cobro, el tipo es P. Si no es ni
    // ingreso ni pendiente de cobro, sera anulado o baja (A ó B).
    $tipo = '';

    // Si alguno de los cargos existentes es un ingreso, no se insertara el cargo nuevo deseado
    // Se insertara el cargo nuevo, si todos los cargos existentes estan pendientes de cobro,
    // y se han marcado los checkbox para anularlos.
    $exisingr = 0;  // Indica si alguno de los cargos existentes es un ingreso
    // Solo se mostrara un cheqbox para anular los cargos existentes que sean pendientes de cobro,
    // y se muestra si no hay ningun ingreso. Para saber en que caso muestro el cheqbox, tengo la
    // variable $exispend, que me indica si ya se mostro el cheqbox. Si el cargo tiene suspención 
    // concedida y causa fuera de ambito no se mostrará el checkbox
    $exispend = 0;
    $exissusp = 0;

    // Recorrer los cargos ya existentes y mostrarlos por pantalla
    while ( $carg = each( $resu ) ) {
      $carg = $carg[value];

      // Esta parte es para mostrar el comienzo de la fila vacia, en el caso de mas de 1 cargo
      // del mismo objeto para el mismo año y periodo
      // Los cargos ya existentes aparecen ordenados por fecha mas reciente
      if ( $i > 0 ) {
        // Cuando tratamos la primera iteracion ($i=0), no se pone el comienzo de la fila vacio, 
        // porque se muestran los datos del cargo que se quiere generar
        print "</tr>\n";
        print "<tr>\n";
        print "<td class = \"derform\" colspan=7></td>\n";
      }
      $i++;
  
      print "<td class = \"derform\" align=\"center\">".Mostrarfecha( $carg[fechcarg] )."</td>\n";
      print "<td class = \"derform\">$carg[periliqu]</td>\n";
      print "<td class = \"derform\">".sql ("SELECT estacont.abre FROM estacont WHERE estacont.codiestacont = '$carg[estacont]'")."</td>\n";
      print "<td class = \"derform\">$carg[nifx]</td>\n";
      print "<td class = \"derform\" nowrap>".impoboni( $carg[deud] )."</td>\n";
      print "<td class = \"derform\"  nowrap>";
      
      $susp_aux = (trim($carg[estasusp]) != null?sql("SELECT estasusp.abre FROM estasusp WHERE estasusp.codiestasusp = '$carg[estasusp]'"):'') . (trim($carg[caussusp]) != null ?' - '.sql("SELECT caussusp.abre FROM caussusp WHERE caussusp.codicaus = '$carg[caussusp]'"):'');

      if ($carg[estasusp] == 'SCO' && $carg[caussusp] == 'FUE'){
      	$exissusp = 1;
      }

      switch ($carg[tipo]) {
        case 'P':
          if ( $exispend == 0 ) {
            // No se mostrara el checkbox si ya fue mostrado anteriormente.
            // Como maximo un solo checkbox por cargo a generar, que si se marca, anularia todos los 
            // cargos existentes que estuvieran pendientes de cobro
            // El cheqbox no se mostrara si uno de los cargos existentes es un ingreso
            if ($carg[estasusp] == 'STR') {
              $nombcheq = "anul".$contobje;
              print "<div id='capacheq$contobje' visibility:visible>\n";
              print "Pasar a PTE. ANULAR ";
              print "<input type = 'checkbox' name = '$nombcheq'>";
              print "</div>\n";
            }
            
            $exispend = 1;

            if ( $tipo != "I" ) {
              // Si hasta ahora, ningun cargo existente es un ingreso, lo pongo como Pendiente
              $tipo = "P";
            }
          }

          if ($carg[estasusp] != 'STR') print $susp_aux; 
          break;
          
        case  'I':
          //print "INGRESO"; 
          $exisingr = 1; 
          $tipo = "I";
          break;
          
        case 'A':
          // print "ANULADO"; 
          // Hasta ahora no hay cargos existentes que sean ingresos o pendientes de cobro
          if ( $tipo != "P" && $tipo != "I" ) { $tipo = "A"; }
          break;
          
        case 'B':
          print "BAJA"; 
          // Hasta ahora no hay cargos existentes que sean ingresos o pendientes de cobro
          if ( $tipo != "P" && $tipo != "I" ) { $tipo = "B"; }
          break;
        }

      print "</td>\n";
    }

    // Si hay algun ingreso, hay que ocultar el checkbox para anular cargos existentes
    // solo si es el caso de liquidacion periodica. Además si el cargo tiene suspención 
    // concedida y causa FUERA DE AMBITO no se mostrará el checkbox
    if ( ($exisingr && $exispend && ( substr( $modoliqu, 0, 1 ) != "D" )) || $exissusp ) {
      print "<script>capacheq$contobje.style.visibility = 'hidden'</script>\n";
    }
  } else {
    // No existian cargos anteriores al que se desea generar para ese objeto, en ese año y periodo
    print "<td class = \"derform\"></td>\n";
    print "<td class = \"derform\"></td>\n";
    print "<td class = \"derform\"></td>\n";
    print "<td class = \"derform\"></td>\n";
    print "<td class = \"derform\"></td>\n";
    print "<td class = \"derform\"></td>\n";
    //print "<td class = \"derform\"></td>\n";
    //print "<input type = 'hidden' name = 'tipo".$contobje."' value = ''>\n";
  }



  // Tipo de los cargos existentes: ingreso, baja, anulacion, pediente de cobro 
  // o vacio (si no hay cargo existentes)
  print "<input type = 'hidden' name = 'tipo".$contobje."' value = '$tipo'>\n";
  // Numero de cargos ya existentes para ese objeto, en ese año y periodo
  print "<input type = 'hidden' name = 'cargexis$contobje' value = '$i'>\n";
  print "</tr>\n";
}


function cheqcarg( $dato, $codiayun, $anio, $periliqu ) {
// Esta funcion comprueba si un objeto que se desea liquidar, ya se liquidó
// anteriormente. Devuelve blanco si no se liquidó anteriormente, o devuelve
// un vector con los valores de la anterior liquidación. 
// Es llamada desde resucarg() cuando deuda() se encuentra mostrando el resumen del cargo a generar
// $dato es el vector con los datos de la liquidación del objeto que se creó
// con la función creavect()

  // Busco la ocurrencia mas reciente de la liquidacion de ese objeto en ese
  // año y periodo de liquidacion (el que tenga la fecha de cargo mas reciente).
  // El mas reciente es la única ocurrencia que podría estar "Pendiente de
  // Cobro", y el resto estarían anuladas o dada de baja.
  $query = "SELECT C.estacont, C.fechcarg, C.periliqu, 
                   C.nifx, C.deud, C.estasusp, C.caussusp, 
                   estacont.tipo, 
                   CASE WHEN C.estasusp = 'STR' THEN 0 ELSE 1 END as orden 
            FROM carg C, estacont 
            WHERE C.codiayun = $codiayun 
              AND C.codiconc = $dato[codiconc]
              AND C.codiobje = '$dato[abreobje]' 
              AND C.anio = '$anio'";
  if ($periliqu == ''){
       $query .= " AND (C.periliqu = '' OR C.periliqu IS NULL)";
  }else{
       $txt_aux = '';
       if ($periliqu == 'PA') $txt_aux = ",'P1','P2'";
       $query .= " AND C.periliqu IN ('$periliqu'$txt_aux)";
  }
  $query .= " AND C.estacont = estacont.codiestacont
              ORDER BY orden, C.fechcarg desc, C.horacarg desc;";
//            AND horacarg = (SELECT max(horacarg) FROM carg CA WHERE anio = '$anio'
//                            AND codiayun = '$codiayun' AND codiconc = '$dato[codiconc]' 
//                            AND codiobje = '$dato[abreobje]' AND periliqu = '$periliqu')
//            AND fechcarg = (SELECT max(fechcarg) FROM carg CAR WHERE anio = '$anio'
//                            AND codiayun = '$codiayun' AND codiconc = '$dato[codiconc]' 
//                            AND codiobje = '$dato[abreobje]' AND periliqu = '$periliqu');";

  $resu = sql( $query );
  if ( is_array( $resu ) ) {
    // Devolver los datos del cargo ya existente para ese objeto
//    $resu = each( $resu );
//    return $resu[value];
    return $resu;
  } else {
    return "";
  }
} 


function datocarg( $dato, $numedocu ) {
// Esta funcion muestra una linea de la tabla del cargo que se ha generado
  print "<tr>\n";
  print "<td class=derform align='right'>".$numedocu."</td>\n";
  print "<td class=derform>".$dato[abreconc]."</td>\n";
  print "<td class=derform>".$dato[abreobje]."</td>\n";
  print "<td class=derform>".$dato[nifx]."</td>\n";
  print "<td class=derform>".$dato[nombsuje]."</td>\n";
  print "<td class=derform>".impoboni($dato[cuot])."</td>\n";
  print "<td class=derform>".impoboni($dato[deud])."</td>\n";

  print "</tr>\n";
}

?>
