<?
// Este script lista los subimportes de las deudas de aquellos conceptos tributarios con
// más de 1 subconcepto.
// Es obligatorio seleccionar el concepto tributario.
// La agrupacion está fijada por estado contable, ejercicio y año contraído.
// La ordenación está fijada por objeto tributario.
include "comun/func.inc";
include "comun/gui.fnc";
include "clas/listado.php";
include "comun/fecha.fnc";

$sesi = cheqsesi(); // chequea la sesión
$codiambi = sql ("SELECT usua.codiambi FROM usua WHERE usua.codiusua = $sesi[sesicodiusua]");

# Comprueba si el usuario tiene permiso para entrar en la página
if ( !cheqperm( "ECOS", $sesi[sesicodiusua] ) ) {
  segu( "Intenta entrar al listado de subimportes de las deudas sin permiso" );
}

// recupera el nombre de página
$estaurlx = estaurlx();

// incluyo los archivos de funciones del lado del cliente (javascript)
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("liqu/" . $estaurlx . ".js") . "'></SCRIPT>\n";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/muestrasconde.js") . "'></SCRIPT>\n";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/hint.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/info.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/misc.js") . "'></SCRIPT>";


//----------------------------------------------------------------------------------------------
// Función que devuelve la clausula WHERE de un query.
// Se utiliza para reconstuir el query en la agrupación Solo Total, para obtener las sumas
// de los importes de las subcuotas.
//----------------------------------------------------------------------------------------------
function conswher( $query ) {
  // Busco las posiciones en que comienzan las clausulas ORDER y GROUP BY para eliminarlas del
  // query que cuenta el número de cargos.
  $posigrup = strpos( $query, " GROUP BY " );
  $posiorde = strpos( $query, " ORDER BY " );
  $posiwher = strpos( $query, " WHERE " );

  // Compruebo la posición en que comienza la agrupación/ordenación en el query.
  // Puede que el query no contenga clausulas ORDER/GROUP BY
  if ( $posigrup && $posiorde ) {
    if ( $posigrup < $posiorde ) {
      $posi = $posigrup;
    } else {
      $posi = $posiorde;
    } 
  } elseif ( $posigrup ) {
    $posi = $posigrup; 
  } elseif ( $posiorde ) {
    $posi = $posiorde; 
  } else {
    $posi = strlen( $query );
  }

  $long = $posi - $posiwher;
  $where = substr( $query, strpos( $query, " WHERE " ), $long );
  return $where;
}


//----------------------------------------------------------------------------------------------
// Función que calcula el número de cargos que muestra el listado.
// Esta función es necesaria porque el listado saca tantos registros como subcuotas tenga cada cargo,
// y lo que queremos en este caso es el recuento de cargo.
// Necesita por parametros el query del listado.
//----------------------------------------------------------------------------------------------
function contcarg( $query ) {
  //Tengo que construir el query que cuente los cargos, a partir de la clausula 
  // WHERE del listado, pero sin tener en cuenta la tabla cargdeud.
  // Tampoco se considera la clausula GROUP/ORDER BY porque si fuera así, tendríamos que añadir al
  // SELECT los campos que están en el GROUP/ORDER BY.

  $querycont = "SELECT count(*) FROM carg ";
  $querycont .= conswher( $query );
  $contregi = sql( $querycont );
  return $contregi;
}


//----------------------------------------------------------------------------------------------
// Función que calcula el número de cargos de cada grupo en el listado SOLO TOTAL.
// Necesita por parametros el query del listado, y los valores de la agrupación en cada caso.
// Recordar que en este caso, siempre se agrupa por estado contable, ejercicio y contraído.
//----------------------------------------------------------------------------------------------
function contgrup( $query, $esta, $ejer, $anio ) {
  //Tengo que construir el query que cuente los grupos, a partir de la clausula 
  // WHERE del listado, pero sin tener en cuenta la tabla cargdeud.
  // Tampoco se considera la clausula GROUP/ORDER BY porque si fuera así, tendríamos que añadir al
  // SELECT los campos que están en el GROUP/ORDER BY.

  $querycont = "SELECT count(*) as contregi, sum(carg.deud) as contdeud FROM carg ";
  $querycont .= conswher( $query );
  $querycont .= " AND carg.estacont = '$esta' AND carg.ejer = '$ejer' AND carg.anio = '$anio' ";
  $resu = sql( $querycont );
  $regi = each( $resu );
  $regi = $regi[value];
  return array( $regi[contregi], $regi[contdeud] );
}


//----------------------------------------------------------------------------------------------
// Función que muestra los totales de los importes de las cuotas, de un grupo o de todo el listado,
// cuando se agrupa por detalle.
// Requiere pasarle por parametros un vector cuyos índices son los codigos de los subconceptos,
// y almacena la subcuota de cada subconcepto.
// El parametro $cate indica si el total se muestra para un grupo o para todo el listado. Hace falta
// porque la tabla del total final del listado es de 3 columnas y el del total de grupo es de 5.
// Los valores de $cate son 'G' o 'F'.
//----------------------------------------------------------------------------------------------
function cuotgrup( $vectcuot, $cate ) {
  // Ordeno el vector de cuotas por el codigo del subconcepto, es decir, por el indice del vector.
  ksort( $vectcuot );
  while( list( $codi, $cuot ) = each( $vectcuot ) ) {
    // Sólo muestro los totales de los subconceptos que estén registrados en la tabla cargdeud.
    // Si la deuda no está desglosada en cargdeud, no muestro nada.
    if ( $codi ) {
      echo "
      <tr>
        <td class ='izqform'";
      if ( $cate == 'G' ) {
        print " colspan='3' ";
      }
      $nombsubc = sql( "SELECT subxconc.nomb FROM subxconc WHERE subxconc.codisubc = $codi" );
      echo "></td>
        <td class ='izqform'>$nombsubc</td>
        <td class ='izqform'>".impoboni($cuot)."</td>
      </tr>
      ";
    }
  }
}


// lanza la cabecera html
cabecera("Desglose de importes");
echo "<BODY><FONT FACE='Arial'>\n";

// página recursiva
echo "<form name='form' method='post' action='$estaurlx.php'>\n";


if ($imprcriterios) {
    $imprcritselec = $imprcriterios;
} else {
    $imprcritselec = 'NO';
}

# PREPARACIÓN DEL OBJETO DE CLASE LISTADO ====================================
// Lista de campos por los que se puede agrupar u ordenar el listado
$desccamp = array (array (),
                   array ('camp' => 'carg.estacont',
                          'nomb' => 'Estado contable'),
                   array ('camp' => 'carg.ejer',
                          'nomb' => 'Ejercicio'),
                   array ('camp' => 'carg.anio',
                          'nomb' => 'Año contraído'),
                   array ('camp' => 'carg.codiobje',
                          'nomb' => 'Objeto Tributario')
                  );

# Campos del listado
$camp = array ( "Nº documento"         => '',
                "Objeto tributario"    => '',
                "Sujeto Pasivo"        => '',
                "Desglose"             => 'return $dato[subxconcnomb];',
                "Importe"              => 'return impoboni($dato[cargdeudsubxdeud]);'
              );

# La ordenacion y la agrupacion esta fijada de antemano: se ordena por codigo de objeto tributario.
# y se agrupa por objeto y subconcepto tributario.
$campagru = array( "Estado contable"   => 'return $extra[$dato[cargestacont]];',
                   "Ejercicio"         => 'return $dato[cargejer];',
                   "Año contraído"     => 'return $dato[carganio];' );
$camporde = array( "Objeto Tributario" => 'return $dato[cargcodiobje];' );

# Criterios de selección del listado
$criterios = array ("+liquidacion-subxdeud",
                    "+objeto-tributario",
                    "+domiciliacion",
                    "+contables-todos");

# Consulta principal del listado
# Los campos deben tener un alias que sea igual a tabla.campo, sin el '.'
$query = "SELECT carg.codiobje as cargcodiobje, carg.numedocu as cargnumedocu, carg.ejer as cargejer,
          carg.anio as carganio, estacont as cargestacont, carg.nifx as cargnifx, 
          carg.deud as cargdeud, carg.codiayun as cargcodiayun, 
          carg.codiconc as cargcodiconc, subxconc.nomb as subxconcnomb,
          CD.codisubc as cargdeudcodisubc, CD.subxdeud as cargdeudsubxdeud
          FROM (carg LEFT JOIN (cargdeud CD INNER JOIN subxconc ON CD.codisubc = subxconc.codisubc) 
          ON carg.codiayun = CD.codiayun AND carg.ejer = CD.ejer 
          AND carg.codiconc = CD.codiconc AND carg.numedocu = CD.numedocu)
          WHERE 1 = 1 ";

// IMPORTANTE: este query es retocado en este mismo script, para contar el número de cargos, en
// lugar del número de subcuotas. La clausula WHERE debe estar escrita en mayusculas para que funcione.


$infotexto = "La selección se realiza sólamente sobre conceptos tributarios que<br> 
              tengan definido más de un subconcepto.<br>
              El desglose de la deuda se refiere únicamente al importe principal.<br>
              La agrupación se realiza por estado contable, ejercicio y año contraído.<br>";

$listado = new Listado (0, $query, $camp, $campagru, $camporde, $criterios, $infotexto, $imprcritselec, $desccamp);

##### muestra pantalla dependiendo de la procedencia de la llamada
if ( $opci == "Listar" ) {

  // Si el usuario ha hecho selecciones, las cogemos como condiciones adicionales
  $listado->aplicaCriterios ();
  
  # Si el listado es de detalles o sólo total
  $mues = ($agrudeta == 'deta')?"detalle":"total";

  // La agrupacion y la ordenacion están fijadas de antemano
  $agrusele = array( 1, 2, 3 );
  $ordesele = array( 4 );
  

  $listado->preparaConsulta ($agrusele, $ordesele, $mues);
  $agru  = $listado->devAgru ();
  $grup1 = $listado->devGrup1();
  $agru2 = $listado->devAgru2();
  $grup2 = $listado->devGrup2();
  $agru3 = $listado->devAgru3();
  $grup3 = $listado->devGrup3();


  $sql = $listado->devQuery();

  // Parche para mostrar el sumatorio de subdeudas, además de las deudas.
  if ( $mues == "total" ) {
    // Añado en la claúsula SELECT, los campos de la tabla cargdeud (codisubc y la suma de la cuota)
    $sql = ereg_replace( " FROM ", ", CD.codisubc as cargdeudcodisubc, sum(subxdeud) as totasubxdeud FROM ", $sql );
    // Tengo que incluir también el campo cargdeud.codisubc en la agrupación.
    // En el caso de Solo Total, como siempre se agrupa porque lo hemos forzado, tendremos la clausula
    // GROUP BY tras la del WHERE, y a continuación la clausula ORDER BY. Luego:
    $sql = ereg_replace( " (GROUP BY .+) ORDER BY", "\\1, CD.codisubc ORDER BY", $sql );

    // De esta forma, la consulta devuelve un registro por cada subconcepto en cargdeud.
    // Si no tuviera subconceptos en cargdeud, porque no está desglosada la deuda, 
    // sólo devuelve un registro con la deuda.
  }

#   print "<br><br><br>depurando ".$sql."<br>";
  $resp = sql ($sql);

  # LO QUE SE ESCONDE AL IMPRIMIR ____________________________________________
  print "<div class='solopantalla'>";
  opci ("Volver:ImprimeListado");
  print "</div>\n";
  # AQUÍ TERMINA LO QUE SE ESCONDE AL IMPRIMIR _______________________________

  # Para que se conserven los criterios de selección 
?>
  <input type='hidden' name='agrusele' value='<?print $agrusele?join ($agrusele, ':'):''?>'>
  <input type='hidden' name='ordesele' value='<?print $ordesele?join ($ordesele, ':'):''?>'>
  <input type='hidden' name='agrudeta' value='<?print $agrudeta?>'>
  <!--  Nombre del checkbox que indica que se imprimen los criterios de selección. -->
  <input type='hidden' name='imprcriterios' value='<?print $imprcriterios?>'>
  <!--  Nombre del checkbox que indica el salto de página entre grupos. -->
  <input type='hidden' name='imprsaltopagi' value='<?print $imprsaltopagi?>'>
<?
  $listado->imprcritsele();
  if ( $imprcriterios == 'SI' ) {
    // Tras mostrar los criterios de seleccion, hay un salto de pagina
    salto();
  }
  $listado->setNumeColum(5);


  print "<center><table cellpadding=4 cellspacing=1 border=0>\n";

  # RESULTADOS DEL LISTADO ===================================================
  if (!$resp) {
    print "<h1>No existen registros para la búsqueda especificada</h1>\n";
  } else {
    // Parche: Contamos los registros encontrados, pero el listado nos devuelve un registro por cada
    // subcuota, en lugar de por cada cargo. 

    print "<div class='solopantalla'>\n";
    print "<h1>Se han encontrado ";
    print contcarg( $sql );
    print " registros</h1>\n";
    print "</div>\n";

    // Variable que nos indica si se puede calcular el salto de pagina.
    // Sólo permitimos que calcule el salto de pagina, tras mostrar
    // todas las subcuotas de cada objeto (en el caso de agrupación por Detalle) o de cada grupo (en 
    // el caso de agrupación Solo Total), para que no corte los datos de un objeto/grupo en dos páginas.
    $calcsalt = 0;

    // Si se trata de una agrupacion con detalle, hay que calcular otro query
    // con la agrupacion del mismo
    if ($listado->detalleGrupos()) {
      $listado->preparaGrupos ();

      // Numero total de cargos
      $total = 0;
      // Numero total de cargos en cada grupo
      $totalgrup = 0;
      // Inicializacion del vector que guarda los totales de cada subconcepto
      $totasubc = array();
      // Inicializacion del vector que guarda los totales de cada subconcepto por grupo
      $totasubcgrup = array();
      // Inicialización del total de la deuda por grupo
      $totaimpogrup = 0;
      // Inicializacion del contador del total de la deuda
      $totaimpo = 0;

      // El listado devuelve un registro por cada subconcepto de un cargo. En la agrupacion en detalle,
      // queremos mostrar el cargo con una fila con sus datos, y a continuación, tantas filas como
      // subimportes tenga. Voy a utilizar ciertas variables que me indicarán si el registro
      // se trata de un cargo nuevo. Los campos para distinguir un cargo de otro son el objeto tributario
      // y el número de documento.
      $actudocu = "";
      $actucodi = "";

    } else {
      // Inicializacion del numero total de registros
      $total = 0;
      // Inicializacion del contador del total de la deuda
      $totaimpo = 0;
      // Inicializacion del vector que guarda los totales de cada subconcepto
      $totasubc = array();

      // El listado por Solo Total devuelve un registro por cada subcuota, mostrando los totales
      // de dichos importes, para cada grupo (estado contable, ejercicio y contraído). Se mostrará
      // una fila con los datos de la agrupación (nº de registros y total de la deuda), y a continuación,
      // tantas filas como subcuotas tenga. Voy a utilizar ciertas variables que me indicarán si el registro
      // se trata de un grupo nuevo. Los campos para distinguir un grupo de otro son el estado contable,
      // el ejercicio y al año contraído.
      $actuesta = "";
      $actuejer = "";
      $actuanio = "";
    }


    // Datos adicionales: nombre del estado contable
    $consulta = sql( "SELECT codiestacont, nomb FROM estacont" );
    while( $info = each( $consulta ) ) {
      $info = $info[value];
      $clav = $info[codiestacont];
      $extra[$clav] = $info[nomb];
    }


    # Este bucle recorre todos los datos.
    # $listado->numeregi es el número de registros que quedan en el grupo
    # actual
    $regi = each($resp);
    while ($regi) {
      // El avance al siguiente registro del vector, para continuar la secuencia del bucle, se
      // efectúa al final del WHILE, para saber si hay cambio de objeto en la agrupación
      // por Detalle, y así determinar si puede calcular el salto de página.

      $dato = $regi[value];
      $nume = $listado->devNumecbox() + 1;   # Número del registro actual

      # CABECERA _____________________________________________________________
      // Muestro la cabecera de los campos de cada cargo, pero solo se muestra si
      // se trata de la primera pagina; el resto solo se muestra en papel.
      $listado->posibleCabePagina (true);


      # IMPRESIÓN DEL REGISTRO ACTUAL ________________________________________
      if ($listado->detalleGrupos()) {
        // Agrupación en detalle
        # Imprimimos la cabecera del grupo, si somos los primeros
        if ($listado->dato_grupos[total] == $listado->numeregi) {
          $eti = array();
          array_push($eti, $grup1 . ': ' . eval ($campagru[$grup1]));
          array_push($eti, $grup2 . ': ' . eval ($campagru[$grup2]));
          array_push($eti, $grup3 . ': ' . eval ($campagru[$grup3]));
          array_push($eti, "" );
          array_push($eti, "" );
          $listado->imprcabesupenew ($eti);
          $listado->incrNumeline (2);
        }


        // Ahora compruebo si el registro actual es el comienzo de un nuevo cargo.
        if ( $actudocu != $dato[cargnumedocu] || $actucodi != $dato[cargcodiobje] ) {
          // Es un nuevo cargo, ya que o el numero de documento es distinto al del registro
          // anterior, o bien la diferencia está en el codigo del objeto tributario.
          $actudocu = $dato[cargnumedocu];
          $actucodi = $dato[cargcodiobje];

          // Mostramos una fila con los datos del cargo.
          $listado->lineaSepa ();
          // Obtengo el nombre del contribuyente principal
          $tercdatonomb = sql( "SELECT tercdato.nomb FROM tercdato WHERE tercdato.nifx = '$dato[cargnifx]'" );
          echo "
            <tr>
              <td class='derform'>$dato[cargnumedocu]&nbsp;</td>
              <td class='derform'>$dato[cargcodiobje]&nbsp;</td>
              <td class='derform'>$dato[cargnifx] / $tercdatonomb &nbsp;</td>
              <td class='derform'>DEUDA&nbsp;</td>
              <td class='derform'>".impoboni($dato[cargdeud])."</td>
            </tr>
          ";

          // Incremento el número de líneas del listado.
          // Aumento este número en dos, si el cargo tiene desglose.
          if ( $dato[cargdeudcodisubc] ) {
            $listado->incrNumeline (2);
          } else {
            $listado->incrNumeline (1);
          }

          // Incremento los totales de registros
          $total++;
          $totalgrup++;
          // Incremento los totales de la deuda
          $totaimpogrup            += $dato[cargdeud];  // Deuda total del grupo
          $totaimpo                += $dato[cargdeud];  // Deuda total
        }

        // Incremento los totales
        $indisubc                 = $dato[cargdeudcodisubc];
        $totasubc[$indisubc]     += $dato[cargdeudsubxdeud];  // Cuota total de cada subconcepto
        $totasubcgrup[$indisubc] += $dato[cargdeudsubxdeud];  // Cuota total de cada subconcepto

      } else {
        // Agrupación solo total
        // Compruebo si el registro actual es el comienzo de un nuevo grupo.
        if ( $actuesta != $dato[cargestacont] || $actuejer != $dato[cargejer] ||
             $actuanio != $dato[carganio]  ) {
          // Es un nuevo grupo, ya que o el estado contable es distinto al del grupo
          // anterior, o bien la diferencia está en el ejercicio o en el año contraído.
          $actuesta = $dato[cargestacont];
          $actuejer = $dato[cargejer];
          $actuanio = $dato[carganio];

          // PARCHE: Calcular el total de cargos dentro de cada grupo. Hay que obtener este dato
          // porque a partir de la consulta obtenemos el total por subconceptos, y esto no es
          // un dato fiable para poder obtener un sumatorio total porque los subconceptos que
          // forman la cuota dentro de un mismo concepto tributario, no tienen porque ser los
          // mismos en todos los cargos, ya que, por ejemplo, en las tasas, se pueden excluir
          // algunos subconceptos.
          // También hay que recalcular el importe de la deuda.
          list( $calctota, $calcimpo ) = contgrup( $sql, $actuesta, $actuejer, $actuanio );
          // Hacemos una chapuza para que el listado funcione y muestre los valores correctos:
          $dato[total] = $calctota;
          $dato[totadeud] = $calcimpo;

          $listado->lineaSepa ();
          // Mostramos una fila con los datos del grupo.
          $listado->imprregi ($dato, "<input type='hidden' name='ayun$nume'
                                             value='$dato[cargcodiayun]'>
                                      <input type='hidden' name='conc$nume'
                                             value='$dato[cargcodiconc]'>
                                      <input type='hidden' name='ejer$nume'
                                             value='$dato[cargejer]'>
                                      <input type='hidden' name='docu$nume'
                                             value='$dato[cargnumedocu]'>", "", 0, true);
          // Incremento los totales de cada cargo.
        $totaimpo += $dato[totadeud];  // Deuda total
        $total    += $dato[total];        // Número de cargos
      }
        // Incremento los totales de cada subcuota.
        $indisubc             = $dato[cargdeudcodisubc];
        $totasubc[$indisubc] += $dato[totasubxdeud];  // Cuota total de cada subconcepto
      }


      // Compruebo si no hay que mostrar los subconceptos porque no hay registro
      // en la tabla cargdeud, es decir, no tiene desglose de la deuda.
      if ( !$dato[cargdeudcodisubc] ) {
        // No hay desglose.
        $nullsubc = 1;
      } else {
        // Hay registros en cargdeud.
        $nullsubc = 0;
      }

        
      if ( !$nullsubc ) {
        // En el caso de agrupacion en detalle, se muestran las subcuotas, empleando imprregi(),
        // si es que tiene desglose.
        // En el caso de agrupación por Solo Total, se muestran cada subcuota en una fila.
        if ($listado->detalleGrupos()) {
          $listado->imprregi ($dato, "<input type='hidden' name='ayun$nume'
                                             value='$dato[cargcodiayun]'>
                                      <input type='hidden' name='conc$nume'
                                             value='$dato[cargcodiconc]'>
                                      <input type='hidden' name='ejer$nume'
                                             value='$dato[cargejer]'>
                                      <input type='hidden' name='docu$nume'
                                           value='$dato[cargnumedocu]'>", "", 0, true);
        } else {
          cuotgrup( array($dato[cargdeudcodisubc] => $dato[totasubxdeud]), 'G' );
          $listado->incrNumeline (2);
        }
      } else {
        // No hay desglose.
        // A pesar de que no mostramos el registro en blanco, incrementamos el numero de
        // líneas para no fallar en los saltos de pagina en la agrupación en detalle.
        if ($listado->detalleGrupos()) {
          $listado->incrNumeline(1);
        }
      }

      # FIN DE GRUPO/PIE DE PÁGINA ___________________________________________
      if ($listado->detalleGrupos ()) {
        $listado->numeregi --;

        if ($listado->finGrupo()) {
          # TOCAR (final de grupos)
          $eti = array();
          array_push($eti, $grup1 . ': ' . eval ($campagru[$grup1]) );
          array_push($eti, $grup2 . ': ' . eval ($campagru[$grup2]) );
          array_push($eti, $grup3 . ': ' . eval ($campagru[$grup3]) );
          array_push($eti, 'Registros: ' . $totalgrup);
          array_push($eti, "Deuda: " . impoboni($totaimpogrup));
          $listado->imprcabeinfenew($eti, $imprsaltopagi);
          $listado->incrNumeline (3);

          // Total de las subcuotas en este grupo
          cuotgrup( $totasubcgrup, 'G' );

          # Reinicializar el total de cada grupo al pasar a un grupo nuevo.
          $totaimpogrup = 0;
          $totalgrup = 0;
          $totasubcgrup = array();

          # Avanzamos de grupo (actualizamos el estado interno)
          $listado->sigGrupo ();
        }
      }
   
      // Pasamos a la siguiente iteración del bucle.
      $regi = each($resp);

      // Compruebo si en la agrupación por Detalle, hay un cambio de objeto, y entonces se puede 
      // calcular el salto de página.
      if ( $listado->detalleGrupos() ) {
        if ( $actudocu != $regi[value][cargnumedocu] || $actucodi != $regi[value][cargcodiobje] ) {
          $calcsalt = 1;
        } else {
          $calcsalt = 0;
        }
      } else {
        // Ahora compruebo si en la agrupación Sólo Total hay un cambio de grupo, y entonces se puede
        // calcular el salto de página.
        if ( $actuesta != $regi[value][cargestacont] || $actuejer != $regi[value][cargejer] || 
             $actuanio != $regi[value][carganio] ) {
          $calcsalt = 1;
        } else {
          $calcsalt = 0;
        }
      }
      if ( $calcsalt ) {
        # Si al final hay salto de página, lo ponemos
        $listado->posibleSalto ("</table>");
      }
    } // Fin while

    # FINAL DEL LISTADO (COMÚN) ==============================================
    ?>
      <input type="hidden" name="numecbox" value="<?print $listado->devNumecbox()?>">
    <?

    print "</table></center></form>\n";

    // Muestro los totales de la deuda y del numero de cargos
    print "<table cellpadding=4 cellspacing=1 border=0 width='100%'>\n";
    $listado->imprcabeinfetota ('Totales finales', "Registros: ".$total, "Deuda: " . impoboni($totaimpo));
    cuotgrup( $totasubc, 'F' );
    print "</table>\n";

  }

  # TERMINA LA IMPRESIÓN DE LA CONSULTA ####################################

} else {

   # SE MUESTRA LA PANTALLA DE CRITERIOS DE SELECCIÓN #######################

   opci("Listar:Limpiar"); // pone los botones
?>

<center>
<table cellpadding=2 cellspacing=1 border=0>
<?
  $listado->cabecrit (1, $imprcriterios, $imprsaltopagi);
?>
 <tr>
   <td class='izqform'>Resultados</td>
   <td class='derform'>
     <input name="agrudeta" type="radio" value="deta"<? if ($agrudeta != 'tota') print " checked"?>>Detalle
     <input name="agrudeta" type="radio" value="tota"<? if ($agrudeta == 'tota') print " checked"?>>Sólo total
   </td>
 <tr>
<?
  $listado->criterios ();
?>
</table>
</center>

<? 
}

  include "comun/pie.inc"; ?>

</body>
