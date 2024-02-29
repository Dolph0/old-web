<?
  # Barra de navegación para resultados paginados.
  # Necesita los siguientes parámetros:
  # - limiinfe, el límite inferior a partir del cual se va a mostrar la página
  #   actual
  # - numeresu, el número de resultados mostrados por página
  # - numecbox, el número TOTAL de resultados de la consulta

  // A la hora de imprimir las notificaciones seleccionadas, hay que descontar del numero TOTAL 
  // de objetos de la consulta numecbox, los que no se han seleccionado
  $contsele = 0;
  // Variable que indica la pagina actual
  $pagi = 1;
  for ( $indi = 1; $indi <= $numecbox; $indi++ ) {
    $regi = eval ('return $regi' . $indi . ';');
    if ( $regi == 'on' ) $contsele++;

    // Determinar la pagina actual a mostrar del total de paginas que se obtienen
    // a partir del primer registro del listado.
    // Incrementar el numero de paginas, hasta que demos con la pagina en la que cae el 
    // registro dado por $limiinfe.
    if ( ( $limiinfe >= $indi ) && ( $contsele > 1 ) && ( ( ($contsele - 1) % $numeresu ) == 0 ) ) 
      $pagi++;
  }

  // Determino el numero total de paginas
  $totapagi = (int)($contsele/$numeresu);
  // Si la division no es exacta, entonces incremento el numero total de pagina, porque
  // hay una ultima pagina en la que se muestran objetos, pero no tantos como indica $numeresu
  if ( $contsele % $numeresu ) $totapagi++;
?>

  <div class=solopantalla>
    <table width="100%"><tr>
      <td class=limpio width="67%">
        <div align=right><? print $numeresu." Resultados por página: Página $pagi de $totapagi ($contsele registros)"; ?>
        </div>
      </td>
      <td class=limpio width="33%">
        <div align=right>
          <? if ($limiinfe + $numeresu - 1 < $contsele) { ?>
            <a href="javascript:form.limiinfe.value = form.limisupe.value; form.submit()">
            Siguientes <?print min ($numeresu, $contsele - ($limiinfe + $numeresu) + 1)?> resultados
            </a>
          <? } ?>
        </div>
      </td>
    </tr></table>
  </div>
