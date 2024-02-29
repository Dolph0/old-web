<?
//----------------------------------------------------------
// Funciones que tienen relación con los plazos de ingreso
//----------------------------------------------------------
include_once "comun/fecha.fnc";
include_once "comun/misc.inc";

//--------------------------------------------------------------
//  Esta función devuelve la fecha del fin del plazo de ingreso
// en función del periodo y la fecha de notificación.
//
//  Entradas:
//    $periodo           => V:Voluntaria ó E:Ejecutiva
//    $fechanotificacion => Po'eso (AAAA-MM-DD)
//
//  Salidas:
//    Fecha de finalización del plazo de ingreso ó la fecha
//  nula(0001-01-01) si hubo algún error.
//--------------------------------------------------------------
function fechafinplazo ($periodo, $fechanotificacion) {
  $fechsalida = '';

  $anio = substr($fechanotificacion, 0, 4);
  $mes = substr($fechanotificacion, 5, 2);
  $dia = substr($fechanotificacion, 8, 2);

  // Filtro
  if (!in_array ($periodo, array('V','E')) ||
      !cheqfech (mostrarfecha($fechanotificacion))) {
    $fechsalida = '0001-01-01';
  }

  // Buscamos la fecha
  if ($fechsalida == '') {
    $sqlplazo = "SELECT diaxfina, mesxfina
                 FROM plazingr
                 WHERE anio = '$anio'
                   AND peri = '$periodo'
                   AND fechnotiinfe <= '$fechanotificacion'
                   AND '$fechanotificacion' <= fechnotisupe
                   AND diaxnotiinfe <= $dia
                   AND $dia <= diaxnotisupe";
    // print "Depurando: ".$sqlplazo;
    $resqlplazo = sql ($sqlplazo);

    if (count($resqlplazo) == 1) {
      $resqlplazo = each($resqlplazo);
      $resqlplazo = $resqlplazo[value];

      $mes += $resqlplazo[mesxfina];
      if ($mes > 12) {
        $mes -= 12;
        $anio++;
      }

      $fechsalida = $anio.'-'.alin ($mes, 2, '0', 'der').'-'.alin ($resqlplazo[diaxfina], 2, '0', 'der') ;
    } else {
      $fechsalida = '0001-01-01';
    }
  }


  // Resultado
  return $fechsalida;
}


?>
