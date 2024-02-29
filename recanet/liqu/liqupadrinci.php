<?
$ruta = "../";
include "comun/func.inc";
include "comun/gui.fnc";
include "comun/fecha.fnc";
include "clas/listado.php";


$sesi = cheqsesi(); // chequea la sesión
$codiambi = sql ("SELECT codiambi FROM usua WHERE codiusua='$sesi[sesicodiusua]'");

# Comprueba si el usuario tiene permiso para entrar en la página
if ( !cheqperm( "gestliqu", $sesi[sesicodiusua] ) )
  segu( "Intenta entrar en gestión de incidencias de liquidación sin permiso" );

// recupera el nombre de página
$estaurlx = estaurlx();

// asigna el archivo de funciones del lado del cliente
// mientras s edepura el enlace a la p'agina por nombre
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("liqu/" . $estaurlx . ".js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/hint.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/misc.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/muestrasconde.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/vent.js") . "'></SCRIPT>";
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/list.js") . "'></SCRIPT>";

// lanza la cabecera html
cabecera("Gestión de incidencias de liquidación");
echo "<BODY><FONT FACE='Arial'>\n";

// página recursiva
echo "<form name='form' method='post' action='$estaurlx.php'>";


# PREPARACIÓN DEL OBJETO DE CLASE LISTADO ====================================
# Lista de campos por los que se puede agrupar u ordenar el listado
$desccamp = array (array (),                                # 0
                         array ('camp' => 'liqupadrinci.modoliqu',
                                'nomb' => 'Tipo de liquidación'),
                         array ('camp' => 'liqupadrinci.codiconc',
                                'nomb' => 'Concepto'),
                         array ('camp' => 'liqupadrinci.peri',
                                'nomb' => 'Periodo')
                        );

# Campos del listado
$camp = array ("Concepto"          => 'return $dato[conctribnomb];',
               "Tipo de liquidación" => 'return $dato[liqutiponomb];',
               "Periodo" => 'return $dato[liquperinomb];',
               "Año" => 'return $dato[anio];',
               "Código tributario" => 'return $dato[codiobje];'
              );

# Campos por los que se puede ordenar
$camporde = array ( "Tipo de liquidación",
					"Concepto",
					"Periodo"
                  );

# Criterios de selección del listado
$criterios = array (
                     "+liquinci"
                   );


# Consulta principal del listado
# Los campos deben tener un alias que sea igual a la tabla.campo, sin el '.'
$query = "SELECT liqupadrinci.codiinci as codiinci, conctrib.nomb as conctribnomb, liqutipo.nomb as liqutiponomb,
				 liquperi.nomb as liquperinomb, anio, liqupadrinci.codiconc as codiconc, liqupadrinci.codiobje as codiobje
                 FROM (((liqupadrinci INNER JOIN (incierro INNER JOIN tipoerro ON incierro.codierro = tipoerro.codierro)
                 ON liqupadrinci.codiinci = incierro.codiinci) INNER JOIN liqutipo ON liqutipo.abre = liqupadrinci.modoliqu)
                 LEFT JOIN conctrib ON liqupadrinci.codiconc = conctrib.codiconc) INNER JOIN liquperi ON liqupadrinci.peri = liquperi.abre
                 WHERE incierro.tipoobje = 'LIQUPADRINCI'";

$listado = new Listado (1, $query, $camp, $campagru, $camporde, $criterios,'','',$desccamp);

if ( $opci == "Volver" ) {
  $delistado = 1;
} else {
  $delistado = 0;
}

if ($opci == 'Eliminar'){
    for ($i = 1; $i <= $numecbox; ++$i) {
      $regi = eval ('return $regi' . $i . ';');
      $codiinci = eval ('return $codi' . $i . ';');
      if ($regi == 'on'){
         $query = "DELETE FROM liqupadrinci WHERE liqupadrinci.codiinci = '$codiinci'";
         if (($result = sql($query)) != "1"){
            print "No se ha podido eliminar la incidencia con codigo = ". $codiinci ."<br>";
            print "Error : " . $result . "<br>";
         }
         $query = "DELETE FROM incierro WHERE incierro.tipoobje = 'LIQUPADRINCI' AND incierro.codiinci = '$codiinci'";
         if (($result = sql($query)) != "1"){
            print "No se ha podido eliminar la incidencia con codigo = ". $codiinci ."<br>";
            print "Error : " . $result . "<br>";
         }
      }
   }
}

##### muestra pantalla dependiendo de la procedencia de la llamada
if ( $opci == "Listar") {
  // Si el usuario ha hecho selecciones, las cogemos como condiciones
  // adicionales
  $listado->aplicaCriterios ();

  # Si el listado es de detalles o sólo total
  $mues = "detalle";

  $listado->preparaConsulta ($agrusele, $ordesele, $mues);

  $agru  = $listado->devAgru ();
  $grup1 = $listado->devGrup1();
  $agru2 = $listado->devAgru2();
  $grup2 = $listado->devGrup2();
  $sql = $listado->devQuery();
  # print "<br><br><br>depurando ".$sql."<br>";
  $resp = sql ($sql);

  # LO QUE SE ESCONDE AL IMPRIMIR ____________________________________________
  print "<div class='solopantalla'>";
  opci ("Volver:ImprimeListado" .
        (cheqperm ("gestliqu", $sesi[sesicodiusua])?":Eliminar":""));
  print "</div>\n";
  print "<div id='botolist' class='solopantalla'>";
  if ($mues == 'detalle' or !$grup1) {
    echo "<input type=button value='Anular selección' onClick=\"asig ('form.regi', '.checked', 1, numecbox.value, '')\">\n
      <input type=button value='Seleccionar todo' onClick=\"asig ('form.regi', '.checked', 1, numecbox.value, 'true')\">\n
      <input type='hidden' name='via' value='volu'>\n";
  }
  print "</div>\n";
  # AQUÍ TERMINA LO QUE SE ESCONDE AL IMPRIMIR _______________________________

  # Para que se conserven los criterios de selección
?>
  <input type='hidden' name='agrusele' value='<?print $agrusele?join ($agrusele, ':'):''?>'>
  <input type='hidden' name='ordesele' value='<?print $ordesele?join ($ordesele, ':'):''?>'>
  <input type='hidden' name='agrudeta' value='<?print $agrudeta?>'>
<?

  print "<center><table cellpadding=4 cellspacing=1 border=0>\n";

  # RESULTADOS DEL LISTADO ===================================================
  if (!$resp) {
    print "<h1>No existen registros para la búsqueda especificada</h1>\n";
    // Oculto los botones de Anular seleccion o seleccionar todos
    print "<script language='JavaScript'>botolist.style.visibility = 'hidden'</script>";
  }
  else {
    if ( ( !$agru && !$agru2) || ( $mues=="detalle" ) ) {
      print "<h1>Se ha" . (count($resp) > 1?"n":"") . " encontrado ";
      print count($resp);
      print " registro" . (count($resp) > 1?"s":"") . "</h1>\n";
    }

    // Si se trata de una agrupacion con detalle, hay que calcular otro query
    // con la agrupacion del mismo
    if ($listado->detalleGrupos()) {
      $listado->preparaGrupos ();

      $total = $listado->numeregi;  # Número de registros por grupo
      $totaimpo     = 0;
      $totaimpogrup = 0;
    }
    else {
      // Aqui inicializo los contadores de deuda y registros totales.
      if ( $mues == "total" ) {
        $totaimpo = 0;
        $total = 0;
      }
    }

    # Este bucle recorre todos los datos.
    # $listado->numeregi es el número de registros que quedan en el grupo
    # actual
    $codierro = '';
    while ($regi = each($resp)) {
      $dato = $regi[value];
      $nume = $listado->devNumecbox() + 1;   # Número del registro actual

      # CABECERA _____________________________________________________________
      $listado->posibleCabePagina ();

      if ($codierro != $dato[codierro]){
         $listado->imprcabeconj($dato[abreerro]);
         $codierro = $dato[codierro];
      }

      if ($listado->detalleGrupos()) {
        # Imprimimos la cabecera del grupo, si somos los primeros
        if ($listado->dato_grupos[total] == $listado->numeregi) {
          $listado->imprcabesupe ($grup1 . ': ' . eval ($campagru[$grup1]),
                        ($grup2 != ''?"$grup2: ".eval ($campagru[$grup2]):""),
                        "Total:&nbsp;$total");
          $listado->incrNumeline (1);
        }
      }
      else {
        // Incremento los contadores de deuda y número de registros totales, en
        // el caso de agrupacion por Solo Total.
        if ( $mues == "total" ) {
          $totaimpo += $dato[totadeud];
          $total += $dato[total];
        }
      }
  
      # IMPRESIÓN DEL REGISTRO ACTUAL ________________________________________
      $listado->imprregi ($dato, "<input type='hidden' name='codi$nume'
                                         value='$dato[codiinci]'>");

      # FIN DE GRUPO/PIE DE PÁGINA ___________________________________________
      if ($listado->detalleGrupos ()) {
        $listado->numeregi --;

        if ($listado->finGrupo()) {
          # TOCAR (final de grupos)
          $listado->imprcabeinfe ('Totales',
                        $grup1 . ': ' . eval ($campagru[$grup1]),
                        ($grup2 != ''?"$grup2: ".eval ($campagru[$grup2]):""),
                        "Registros: " . $total,
                        "Total:&nbsp;" . impoboni($totaimpogrup));
          $listado->incrNumeline (1);

          # TOCAR (cálculos de totales optativos)
          $totaimpogrup = 0;

          # Avanzamos de grupo (actualizamos el estado interno)
          $total = $listado->sigGrupo ();
        } else {
          # Separación entre registros (no si es el último de su grupo)
          $listado->lineaSepa ();
        }
      }

      # Si al final hay salto de página, lo ponemos
      $listado->posibleSalto ("</table>");
    } // Fin while



    # FINAL DEL LISTADO (COMÚN) ==============================================
    ?>
      <input type="hidden" name="numecbox" value="<?print $listado->devNumecbox()?>">
    <?

    # Datos finales (que sólo hay que imprimir si se muestran detalles y se
    # agrupó por algún campo), como firmas de "Recibí" y totales finales
    if ($listado->detalleGrupos()) {
      # TOCAR (total final)
      $listado->imprcabeinfe ('Totales finales', '', '', "Registros: ".$listado->numeregitota, "Total: " . impoboni($totaimpo));

      print "</table></center></form>\n";

      $listado->incrNumeline (2);
    } else {
        print "</table>\n</center>\n";
        // Muestro por pantalla el total de la deuda y del número de registros
        if ( $mues == "total" ) {
          $listado->elec = 0;
          print "<table width='100%' cellpadding=4 cellspacing=1 border=0>\n";
          $listado->imprcabeinfetota ('Totales finales', "Registros: ".$total, "Total: " . impoboni($totaimpo));
          print "</table>\n";
      }
    }

  }
  # TERMINA LA IMPRESIÓN DE LA CONSULTA ####################################

} else {

  # SE MUESTRA LA PANTALLA DE CRITERIOS DE SELECCIÓN #######################

  opci("Listar:Limpiar"); // pone los botones
?>

<center>
<table width=100% cellpadding=2 cellspacing=1 border=0>
<?
  $listado->cabecrit (1);
  $listado->cajaOrde (3, array ("Tipo de liquidación", "Concepto", "Periodo"), $ordesele);
  $listado->criterios ();
?>
</table>
</center>
<?
  $fallos = sql ("SELECT count (*) FROM incierro where tipoobje='LIQUPADRINCI'");
  echo "<p>";
  iniccaja2 ("criteriosdecision","Existen $fallos incidencias pendientes", 'block');
  finacaja2 ();

 } ?>
<? include "comun/pie.inc"; ?>

</body>
