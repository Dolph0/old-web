<?
// Este script lista los padrones (liquidaciones periodicas) de un concepto tributario
// concreto, para un periodo de liquidacion y a�o contraido dado.
// El usuario debe seleccionar dichas condiciones de busqueda.
include "comun/func.inc";
include "comun/gui.fnc";
include "comun/dire.fnc";
include "clas/listado.php";
include "comun/fecha.fnc";

$sesi = cheqsesi(); // chequea la sesi�n
$codiambi = sql ("SELECT usua.codiambi FROM usua WHERE usua.codiusua = $sesi[sesicodiusua]");

# Comprueba si el usuario tiene permiso para entrar en la p�gina
if ( !cheqperm( "ECOS", $sesi[sesicodiusua] ) )
  segu( "Intenta entrar al listado de padrones sin permiso" );

// recupera el nombre de p�gina
$estaurlx = estaurlx();

// incluyo los archivos de funciones del lado del cliente (javascript)
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("liqu/$estaurlx.js") . "'></SCRIPT>\n";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/muestrasconde.js") . "'></SCRIPT>\n";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/hint.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/info.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/misc.js") . "'></SCRIPT>";

// lanza la cabecera html
cabecera("Listado de padrones");
echo "<BODY><FONT FACE='Arial'>\n";

// p�gina recursiva
echo "<form name='form' method='post' action='$estaurlx.php'>\n";

// Filtros en caso de TASAS - OTPP
$tipoconc = null;
if ($conctrib > 0) $tipoconc = sql ("SELECT conctrib.tipo FROM conctrib WHERE conctrib.codiconc = $conctrib"); 

# PREPARACI�N DEL OBJETO DE CLASE LISTADO ====================================
# Campos del listado
if ($tipoconc == 'T' && $refeterr) {
  $camp = array ( "C�digo tributario" => 'return $dato[cargcodiobje];',
                   "Referencia"           => 'return $dato[cargotpprefetasa];',
                   "Domicilio tributario" => 'return $dato[cargdomitrib];',
                   "NIF"                => 'return $dato[cargnifx];',
                   "Nombre sujeto pasivo" => 'return $dato[tercdatonomb];',
                   "N� documento"       => 'return $dato[cargnumedocu];',
                   "Deuda"                => 'return impoboni($dato[cargdeud]);'
                );
} else {
  $camp = array ("C�digo tributario"    => 'return $dato[cargcodiobje];',
                 "NIF"                  => 'return $dato[cargnifx];',
                 "Nombre sujeto pasivo" => 'return $dato[tercdatonomb];',
                 "N� documento"         => 'return $dato[cargnumedocu];',
                 "Deuda"                => 'return impoboni($dato[cargdeud]);'
                );
}

# La ordenacion y la agrupacion esta fijada de antemano: no se agrupa
# y se ordena por nombre del titular y codigo tributario
$campagru = "";
$camporde = "";

# Criterios de selecci�n del listado
$criterios = array ("+padron");

# Consulta principal del listado
# Los campos deben tener un alias que sea igual a la tabla.campo, sin el '.'
   $select = "SELECT carg.codiobje as cargcodiobje, 
                    carg.numedocu as cargnumedocu, 
                    carg.nifx as cargnifx, 
                    tercdato.nomb as tercdatonomb, 
                    carg.deud as cargdeud, 
                    carg.codiayun as cargcodiayun, 
                    carg.codiconc as cargcodiconc ";
   $from  = " FROM tercdato, carg ";
   $where = " WHERE carg.codiayun = $codiambi 
                AND carg.nifx = tercdato.nifx ";

   if ($tipoconc == 'T' && $refeterr) { 
      // TASAS
      $select .= ",siglvias.siglviax, 
                   vias.nomb as viasnomb, 
                   cargotpp.nume as cargotppnume,
                   cargotpp.letr as cargotppletr, 
                   cargotpp.esca as cargotppesca, 
                   cargotpp.plan as cargotppplan, 
                   cargotpp.puer as cargotpppuer,
                   cargotpp.refetasa as cargotpprefetasa ";
                       
      $from   .= ", ((cargotpp LEFT JOIN vias ON cargotpp.codiviax = vias.codiviax)
                       LEFT JOIN siglvias ON siglvias.codisigl = vias.codisigl) ";
      $where  .= " AND cargotpp.codiayun = carg.codiayun
                   AND cargotpp.codiconc = carg.codiconc 
                   AND cargotpp.ejer     = carg.ejer
                   AND cargotpp.numedocu = carg.numedocu ";
   } 

   $query = $select.$from.$where;

   $listado = new Listado (0, $query, $camp, $campagru, $camporde, $criterios);
   if ($tipoconc == 'T' && $refeterr) $listado->setNumecolum (7);

##### muestra pantalla dependiendo de la procedencia de la llamada
if ( $opci == "Listar" ) {

     // Si el usuario ha hecho selecciones, las cogemos como condiciones
  // adicionales
  $listado->aplicaCriterios ();
  
  # Solo se imprime solo total
  $mues = "detalle";
  

  // Comprobar si el padron a listar se encuentra aun en la tabla padrpend, a falta
  // de confirmar el padron, o bien, que aun se est� generando.
  $query2 = "SELECT padrpend.codiayun,
                    padrpend.codiconc,
                    padrpend.anio,
                    padrpend.periliqu,
                    padrpend.fech,
                    padrpend.hora,
                    padrpend.erroobje
             FROM padrpend 
             WHERE padrpend.codiayun = $ayun 
               AND padrpend.codiconc = $conctrib
               AND padrpend.periliqu = '$liquperi' 
               AND padrpend.anio = '$anio' ";
  $resu = sql( $query2 );
  if ( is_array( $resu ) ) {
    // El padron que desea listar, a�n est� en la tabla padrpend.
    // As� que o bien est� a falta de confirmar el padron, o bien est� generandose
    // el padron en estos momentos.
    opci("Volver");
    print "<br><br>\n";
    print "<b>El padr�n est� a�n pendiente de confirmaci�n, o bien, se est� generando en estos momentos.</b>";
    print "<br><br>\n";
  } else {
    // El padr�n no est� en la tabla padrpend.
    // Pero puede no existir, porque no se haya generado.

    $sql = $listado->devQuery();
    // La ordenacion est� fijada de antemano, y no se agrupa por ningun campo
    $sql .= " ORDER BY tercdatonomb, codiobje";
    // print "<br><br><br>depurando ".$sql."<br>";
    $resp = sql ($sql);
  
    # LO QUE SE ESCONDE AL IMPRIMIR ____________________________________________
    print "<div class='solopantalla'>";
    opci ("Volver:ImprimeListado");
    print "</div>\n";
    # AQU� TERMINA LO QUE SE ESCONDE AL IMPRIMIR _______________________________
  
    # Para que se conserven los criterios de selecci�n
    print "<center><table cellpadding=4 cellspacing=1 border=0>\n";
  
    # RESULTADOS DEL LISTADO ===================================================
    if (!$resp) {
      print "<h1>No existen registros para la b�squeda especificada</h1>\n";
    }
    else {
      print "<h1>Se han encontrado ";
      print count($resp);
      print " registros</h1>\n";
  
      // Inicializacion del numero total de registros
      $total = 0;
      // Inicializacion del contador del total de la deuda
      $totaimpo = 0;
  
      // Obtengo el campo nombre del periodo de liquidacion, segun lo seleccionado
      // en las condiciones de busqueda, para ponerlo en el titulo del listado.
      $nombperi = sql( "SELECT liquperi.nomb FROM liquperi WHERE liquperi.abre = '$liquperi'" );
      // Y tambien el nombre del concepto tributario
      $nombconc = sql( "SELECT conctrib.nomb FROM conctrib WHERE conctrib.codiconc = $conctrib" );
      // Para el mismo titulo, obtengo la fecha en que se gener� el padron,
      // pero �ste pudo generarse en dos dias (atraves� la media noche), pero
      // da igual, solo mostramos uno cualquiera de esos dias.
      $fechcarg = sql( "SELECT carg.fechcarg FROM carg WHERE carg.codiayun = $ayun AND carg.codiconc = $conctrib AND carg.modoliqu = 'PER' AND carg.anio = '$anio' AND carg.periliqu = '$liquperi' LIMIT 1" );
  
      # Este bucle recorre todos los datos.
      # $listado->numeregi es el n�mero de registros que quedan en el grupo
      # actual
      while ($regi = each($resp)) {
        $dato = $regi[value];
        $nume = $listado->devNumecbox() + 1;   # N�mero del registro actual
  
        # CABECERA _____________________________________________________________
        // Muestro un titulo con los datos del padron que se ha listado
        // Se muestra cuando el contador de registros en la pagina se inicializa a
        // 1 tras cada salto de pagina, o cuando sea la primera pagina.
        if ( $listado->numeline == 1 ) {
          print "<table width='100%' cellpadding='2' cellspacing='1' border='0'>\n";
            $eti = array();
            array_push($eti, $nombconc);
            array_push($eti, "Periodo $nombperi $anio");
            array_push($eti, "Fecha del cargo ".Mostrarfecha( $fechcarg ));
            $listado->imprcabesupenew($eti);

//          $listado->imprcabesupe( $nombconc, "Periodo $nombperi $anio", "Fecha del cargo ".Mostrarfecha( $fechcarg ) );
          print "</table>\n";
        }
  
        // Muestro la cabecera de los campos de cada cargo, pero solo se muestra si
        // se trata de la primera pagina; el resto solo se muestra en papel.
        $listado->posibleCabePagina ();
  
        // Incremento el contador del total de la deuda, y del numero de cargos
        $totaimpo += $dato[cargdeud];
        $total++;
  
        // Domicilio tributario
        if ($tipoconc == 'T' && $refeterr) $dato[cargdomitrib] = mostrarDireccion ($dato[siglviax], $dato[viasnomb], $dato[cargotppnume], $dato[cargotppplan], $dato[cargotppletr], $dato[cargotppesca], $dato[cargotpppuer]); 
        else $dato[cargdomitrib] = '';
        
        # IMPRESI�N DEL REGISTRO ACTUAL ________________________________________
        $listado->imprregi ($dato, "<input type='hidden' name='ayun$nume'
                                           value='$dato[cargcodiayun]'>
                                    <input type='hidden' name='conc$nume'
                                           value='$dato[cargcodiconc]'>
                                    <input type='hidden' name='ejer$nume'
                                           value='$dato[cargejer]'>
                                    <input type='hidden' name='docu$nume'
                                           value='$dato[cargnumedocu]'>");
  
        # Si al final hay salto de p�gina, lo ponemos
        $listado->posibleSalto ("</table>");
      } // Fin while
  
      # FINAL DEL LISTADO (COM�N) ==============================================
      ?>
        <input type="hidden" name="numecbox" value="<?print $listado->devNumecbox()?>">
      <?
  
      print "</table></center>\n";
  
      // Muestro los totales de la deuda y del numero de cargos
      print "<table cellpadding=4 cellspacing=1 border=0 width='100%'>\n";
      $listado->imprcabeinfetota ('Totales finales', "Registros: ".$total, "Total: " . impoboni($totaimpo));
      print "</table>\n";
    }
  
    # TERMINA LA IMPRESI�N DE LA CONSULTA ####################################

  }
} else {
   if ( $opci == "XLS" ) {
      # Consulta principal del listado
      # Los campos deben tener un alias que sea igual a la tabla.campo, sin el '.'
      if ($tipoconc == 'T' && $refeterr) {
        $query = "SELECT '\''||carg.codiobje||'\'', 
                         siglvias.siglviax || ' ' || vias.nomb || ' ' || cargotpp.nume || ' ' || cargotpp.letr || 
                         CASE WHEN (trim(cargotpp.esca) <> '' or trim(cargotpp.plan) <> '' or trim(cargotpp.puer) <> '') THEN
                           ', '
                         ELSE 
                           ''
                         END
                         ||
                         CASE WHEN  (cargotpp.esca = 'T' and cargotpp.plan = 'OD' and cargotpp.puer = 'OS') THEN
                            ' (TODOS)'
                         WHEN (cargotpp.esca = 'S' and cargotpp.plan = 'OL' and cargotpp.puer = 'AR') THEN
                            ' (SOLAR)'
                         WHEN (cargotpp.esca = 'S' and cargotpp.plan = 'UE' and cargotpp.puer = 'LO') THEN 
                            ' (SUELO)'
                         ELSE
                            CASE WHEN (trim(cargotpp.esca) <> '') THEN ' Esc.' || cargotpp.esca ELSE '' END 
                            ||
                            CASE WHEN (trim(cargotpp.plan) <> '') THEN ' Pl.' || cargotpp.plan ELSE '' END
                            ||
                            CASE WHEN (trim(cargotpp.puer) <> '') THEN ' Pta.' || cargotpp.puer ELSE '' END
                         END as domitribu,
                         cargotpp.refetasa,
                         carg.nifx, 
                         tercdato.nomb, carg.numedocu, round(round(carg.deud,2)/100,2) 
        FROM  tercdato, carg, ((cargotpp LEFT JOIN vias ON cargotpp.codiviax = vias.codiviax) LEFT JOIN siglvias ON siglvias.codisigl = vias.codisigl)
        WHERE carg.nifx = tercdato.nifx AND cargotpp.codiayun = carg.codiayun AND cargotpp.codiconc = carg.codiconc AND cargotpp.ejer = carg.ejer AND cargotpp.numedocu = carg.numedocu";
      } else {
        $query = "SELECT '\''||carg.codiobje||'\'', carg.nifx, tercdato.nomb, carg.numedocu, round(round(carg.deud,2)/100,2) FROM  tercdato, carg WHERE carg.nifx = tercdato.nifx";
      }

      $listado = new Listado (0, $query, $camp, $campagru, $camporde, $criterios);

     // Si el usuario ha hecho selecciones, las cogemos como condiciones
     // adicionales
     $listado->aplicaCriterios ();
  
     // Comprobar si el padron a listar se encuentra aun en la tabla padrpend, a falta
     // de confirmar el padron, o bien, que aun se est� generando.
     $query2 = "SELECT padrpend.codiayun,
                       padrpend.codiconc,
                       padrpend.anio,
                       padrpend.periliqu,
                       padrpend.fech,
                       padrpend.hora,
                       padrpend.erroobje
                FROM padrpend 
                WHERE padrpend.codiayun = $ayun 
                  AND padrpend.codiconc = $conctrib
                  AND padrpend.periliqu = '$liquperi' 
                  AND padrpend.anio = '$anio'";
     $resu = sql( $query2 );
     if ( is_array( $resu ) ) {
       // El padron que desea listar, a�n est� en la tabla padrpend.
       // As� que o bien est� a falta de confirmar el padron, o bien est� generandose
       // el padron en estos momentos.
       opci("Volver");
       print "<br><br>\n";
       print "<b>El padr�n est� a�n pendiente de confirmaci�n, o bien, se est� generando en estos momentos.</b>";
       print "<br><br>\n";
     } else {
       // El padr�n no est� en la tabla padrpend.
       // Pero puede no existir, porque no se haya generado.

       $sql = $listado->devQuery();
       // La ordenacion est� fijada de antemano, y no se agrupa por ningun campo
       $sql .= " ORDER BY tercdato.nomb, codiobje";
       // print "<br><br><br>depurando ".$sql."<br>"; exit;
       $query = $sql;
       $concepto = sql ("SELECT conctrib.nomb FROM conctrib WHERE conctrib.codiconc = $conctrib");
       $periodo = sql ("SELECT liquperi.nomb FROM liquperi WHERE liquperi.abre = '$liquperi'");
       
       // Variables del listado excel.
       $titu = "Listado del Padr�n";
       if ($tipoconc == 'T' && $refeterr) {
         $cabe = "C�digo Tributario, Domicilio tributario, Referencia, NIF, Nombre, N� Documento, Deuda(�)";   
       } else {
         $cabe = "C�digo Tributario, NIF, Nombre, N� Documento, Deuda(�)";   
       }
       
       $selec = "Concepto, ".ereg_replace(',','',$concepto).
                ", Per�odo, '".ereg_replace(',','',$periodo).
                "', A�o contra�do, ".ereg_replace(',','',$anio);
       
       // Pasamos las variables para el listado EXCEL
       // IMPORTANTE: No cambiar las comillas dobles por simples, provoca errores en la recepcion
       //             de las variables.  
       ?>
         <input type = 'hidden' name = 'titu' value = "<? print $titu; ?>">
         <input type = 'hidden' name = 'selec' value = "<? print $selec; ?>">
         <input type = 'hidden' name = 'query' value = "<? print $query; ?>"> 
         <input type = 'hidden' name = 'cabe' value = "<? print $cabe; ?>"> 
         <input type = 'hidden' name = 'tipo' value = "1"> 
       <?
       
       echo "
         <script>
           enviform (form, '../comun/docxls.php');       
           // location.href = '$estaurlx.php';
           form.submit ();
         </script>";    
       
     }   
  }else{

   # SE MUESTRA LA PANTALLA DE CRITERIOS DE SELECCI�N #######################

   opci("Listar:Limpiar:XLS"); // pone los botones
?>

<center>
<table cellpadding=2 cellspacing=1 border=0>
<?
  $listado->cabecrit (1);
  $listado->criterios ();
?>
</table>
</center>

<? } 
}

  include "comun/pie.inc"; ?>

</form>
</body>
