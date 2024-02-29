<?
  # Funciones que tienen relación con los cargos (consultan sus propiedades,
  # las interpretan, etc.)

function ImpoDemo( $estacont, $fechestacont, $fechfinavolu,
                   $fechfinaprov, $deud, $fech ) {

  #  include "../comun/fecha.fnc";

  # calcula los intereses de demora
  # Parámetros de entrada
  #
  # estacont     : de carg.estacont
  #                Durante el periodo de cálculo debe ser pte. de cobro
  # fechestacont : de carg.fechestacont
  #                Fecha en q cambió el estado contable
  #                  normalmente a # pte. cobro
  #                Formato aaaammdd
  # fechfinavolu : de carg.fechfinavolu
  #                Fecha a partir de la que empieza el cálculo
  #                Formato aaaammdd
  # fechfinaprov : de carg.fechfinaprov
  #                Tiene que ser anterior a la fecha de cálculo
  #                Formato aaaammdd
  # deud         : de carg.deud
  # fech         : fecha en la que se quiere conocer el cálculo de I.de demora
  #
  # Salida: entero con el cálculo del importe de demora


  # Sustitución simple para poder pasar los parámetros de fecha tal cual de la
  # base de datos (con guiones)
  $fechestacont = preg_replace ('/-/', '', $fechestacont);
  $fechfinavolu = preg_replace ('/-/', '', $fechfinavolu);
  $fechfinaprov = preg_replace ('/-/', '', $fechfinaprov);
  $fech         = preg_replace ('/-/', '', $fech);

  #
  # Si no está en ejecutiva en la fecha del cálculo
  #
  if ( ( $fechfinaprov == "" ) or ( $fechfinaprov == "00010101")
    or ( $fech <= $fechfinaprov ) ) $impodemo = 0;
  else {
    # calcula entre q fechas hacer el cálculo
    # ambos días inclusive
    # para el inicio toma el día siguiente
    $fechinic = SumaDias( $fechfinavolu, 1 );
    if ( $fechinic == -1 ) $impodemo = -1;
    else {
      # para el final, depende del estado
      if ( $estacont == "PEN" ) $fechfina = SumaDias( $fech, -1 );
      else
        # toma la primera
        if ( $fech > $fechestacont ) $fechfina = SumaDias( $fechestacont, -1 );
        else $fechfina = SumaDias( $fech, -1 );
      if ( $fechfina == -1 ) $impodemo = -1;
      else {
        if ( $fechinic > $fechfina ) $impodemo = 0;
        else {
          # divide en periodo incluidos en el año natural
          # fecha de inicio del primer periodo
          $fechperiinic = $fechinic;
          # año del primer periodo
          $anioperi = substr( $fechperiinic, 0, 4);
          # año del último periodo
          $aniofina = substr( $fechfina, 0, 4);
          $impodemo = 0;
          // print "depurando antes del bucle <br>";

          # Antes que nada, recogemos de la base de datos los valores del
          # interés de demora que necesitará impodemoperi, para pasárselos como
          # parámetro
          # NOTA: "anio" es para forzar a que sql devuelve *siempre* una matriz
          $query = "SELECT anio, intedemo FROM valoanua WHERE anio >= $anioperi AND anio <= $aniofina ORDER BY anio";
          $resu = sql ($query);
          if (! is_array ($resu)) {
            print "¡ERROR! NO SE ENCUENTRAN LOS INTERESES DE DEMORA ENTRE LOS AÑOS $anioperi Y $aniofina<br>\n";
            exit;
          }

          while ( $anioperi <= $aniofina ) {
            $dato = each ($resu);
            $dato = $dato[value];
            // print "depurando añoperi=$anioperi, añofina=$aniofina,
              // fechinic = $fechinic, fechfina=$fechfina <br>";
            # si no es el último periodo coge el último día del año
            if ( $anioperi < $aniofina ) $fechperifina = $anioperi."1231";
            else $fechperifina = $fechfina;
            $impodemo += ImpoDemoPeri( $fechperiinic, $fechperifina, $deud, $dato[intedemo] );
            // print "impodemo = $impodemo <br>";
            # si es el último periodo sale
            if ( $anioperi == $aniofina ) $anioperi = $aniofina +1;
            else {
              # fecha de inicio del periodo
              $fechperiinic = SumaDias( $fechperifina, 1 );
              # año del periodo
              $anioperi = substr( $fechperiinic, 0, 4 );
            }
          }
        }
      }
    }
  }
  return euro2cent ($impodemo);
}

function ImpoDemoPeri( $fechinic, $fechfina, $deud, $tipointe = 'nada') {
  # calcula el IMPOrte de DEMOora en un PERIodo que
  # está dentro de un año natural
  # Sólo es llamada por la función ImpoDemo
  #
  # Parámetros de entrada
  #
  #  fechinic fecha que indica el día de inicio del periodo de cálculo.
  #     Este día se incluye en el cálculo
  #     Formato aaaammdd
  #
  #  fechfina fecha que indica el día de fin del periodo de cálculo.
  #     Este día se incluye en el cálculo
  #     Formato aaaammdd
  #
  #  deud importe sobre el que calcular el interés

  # Obtiene el año
  $anio = (int)substr( $fechinic, 0, 4 );

  # Verifica que ambas fechas sean del mismo año natural
  if ( $anio != (int)substr( $fechfina, 0, 4 ) ) $impodemo = -1 ;
  else {

    # Cálcula el número de días
    $numedias = NumeDias( $fechinic, $fechfina ) ;
    if ( $numedias == -1 ) $impodemo = -1;
    else {
      # incluye ambos límites
      $numedias++;

      # Si hay errores de formato o los límites están cruzados o fuera de
      # rango
      if ( $numedias == -1 ) $impodemo = -1 ;
      else {

        # Obtiene el año
        $anio = (int)substr( $fechinic, 0, 4 );

        # Calcula si es bisiesto
        if ( checkdate( 2, 29, $anio ) ) $bisi = 1;
        else $bisi = 0;

        # Busca el tipo de interés por demora del año
        if ($tipointe == 'nada')      # Compatibilidad con versión anterior
          $tipointe = sql( "select intedemo from valoanua where anio = $anio" );
        # Si no está se pone a 0 porque a -1 confundiría el cálculo
        # V2. incidencias

        # Cálcula el importe
        if ( $tipointe == "" ) $impodemo = "0";
        else $impodemo = ( $deud * $tipointe / 10000 ) *
                         ( $numedias / ( 365 + $bisi ) ) ;

        // print "depurando tipo= $tipointe, anio= $anio, numedias=$numedias,
          // bisi=$bisi, deud=$deud <br>";
      }
    }
  }
  return( $impodemo );
}



# Calcula el porcentaje de recargo de un cargo. Recibe el estado contable, la
# vía *efectiva*, el porcentaje de recargo, el recasino o recanono (el que se
# aplique) del año del que se quiere calcular el porcentaje, el tipo de
# objeto, el modo de liquidación, la fecha de pago (normalmente, el mismo
# día) y la fecha de final de periodo voluntario de cobro.
# Ambas fechas deben estar en el formato de la B.D.
function porcreca ($estacont, $via, $porcreca, $recaxxno, $tipoobje,
                   $modoliqu, $fechpago, $fechfinavolu) {
    if ($estacont == 'PEN') {
      if ($via == 'VOLUNTARIA') {
        return $porcreca;
      } else {
        if ($tipoobje == 'OIPL' and $modoliqu == 'AUT') {
          $difemeses = numeMeses ($fechfinavolu, $fechpago);

          if ($difemeses < 0) {
            print "porcreca: error: La fecha de pago (" .
                  mostrarFecha ($fechpago) .
                  ") es menor que la fecha de final del plazo de voluntaria (" .
                  mostrarFecha ($fechfinavolu) . ")\n";
            return 0;
          } elseif ($difemeses <= 3) {
            return 5;
          } elseif ($difemeses <= 6) {
            return 10;
          } elseif ($difemeses <= 12) {
            return 15;
          } else {
            return 20;
          }
        } else return $recaxxno;
      }
    } else return $via == 'VOLUNTARIA' ? 0 : $porcreca;
}


#----------------------------------------------------------------------
# Devuelve la vía efectiva de un cargo (no es necesariamente la que está
# guardada en la B.D., que por cierto se da como parámetro en $pericont)
# El formato devuelto por la función es para imprimir ('VOLUNTARIA' o
# 'EJECUTIVA'). Devolverá 'N/A' si pericont es incorrecto.
#
# Entradas:
#   $estacont     => Estado contable
#   $pericont     => Periodo contable
#   $fechfinavolu => Fecha final plazo de voluntaria (YYYY-MM-DD)
#   $estanotivolu => Estado notificacion de voluntaria
#   $fecha        => Fecha sobre la que trabajamos (YYYY-MM-DD), por
#                    omisión es hoy.
#
# Salidas:
#   "VOLUNTARIA" ó "EJECUTIVA".
#----------------------------------------------------------------------
function viaefec ($estacont, $pericont, $fechfinavolu, $estanotivolu, $fecha = '') {
  if ($fecha == '') $fecha = date("Y-m-d",time());
  
  if ($estacont != 'PEN') {
    switch ($pericont) {
      case 'V': return 'VOLUNTARIA';
      case 'E': return 'EJECUTIVA';
      default:  return 'N/A';
    }
  } elseif ($fechfinavolu == '0001-01-01' or $fechfinavolu == '') {
    return 'VOLUNTARIA';
  } else {
    if ( !ereg( '^N', $estanotivolu ) ) {
      // Si aún no ha sido notificado, se mantiene en periodo voluntario.
      return 'VOLUNTARIA';
    } else {
      return ($fecha <= $fechfinavolu)?'VOLUNTARIA':'EJECUTIVA';
    }
  }
}



// Funcion que devuelve el valor de recasino o recanono de la tabla valoanua,
// según el estado de notificación y las fechas de notificación y estado.
/*
function recaxxno ($fechesta,$fechnotiprov,$estanotiprov)
{
  if ($fechesta)    $anioactual=substr ($fechesta,-4);
  else     $anioactual=date (Y,time());

  if (substr ($estanotiprov,0,1)=='N'){
    if (compfech($fechesta, $fechnotiprov)>1)
       $recaxxno=sql("SELECT recanono FROM valoanua WHERE anio=$anioactual");
    else
       $recaxxno=sql("SELECT recasino FROM valoanua WHERE anio=$anioactual");
  }else{
     $recaxxno=sql("SELECT recanono FROM valoanua WHERE anio=$anioactual");
  }
  return $recaxxno;
} */

//--------------------------------------------------------------------
// Esta función devuelve el porcentaje de recargo.
//
// Entradas:
//   $fechinicejec => Fecha inicio periodo ejecutivo (AAAA-MM-DD) (fechfinavolu + 1)
//   $fechnotiprov => Fecha notificación de la providencia de apremio (AAAA-MM-DD)
//   $fechfinaproc => Fecha fin periodo ejecutiva (AAAA-MM-DD)
//   $fecha        => Fecha sobre la que trabajamos (AAAA-MM-DD)
//   $estanotiprov => Tipo de notificación
//
// Salida:
//   Porcentaje de recargo.
//--------------------------------------------------------------------
function recaxxno ($fechinicejec, $fechnotiprov, $fechfinaprov, $fecha, $estanotiprov) {
  $recaejec = 0;
  $recaredu = 0;
  $recaordi = 0;
  $reca     = 0;

  // print $fechinicejec.', '.$fechnotiprov.', '.$fechfinaprov.', '.$fecha.', '.$estanotiprov.'<br>';

  $anioejec = substr ($fechinicejec, 0, 4);

  if ($fechinicejec && $anioejec != '0001') $anio = $anioejec;
  else $anio = date (Y, time());

  $queryvaloanua = "SELECT * FROM valoanua WHERE anio = '$anio'";
  $resuvaloanua = sql($queryvaloanua);
  // print 'Depurando: '.$queryvaloanua;

  if (is_array ($resuvaloanua) && count ($resuvaloanua) == 1) {
    $regi = each($resuvaloanua);
    $dato = $regi[value];

    $comp = compfech (mostrarfecha($fechinicejec), mostrarfecha($dato[recafech]));

    switch ($comp) {
      case 0: // $fechinicejec == $dato[recafech]
      case 1: // $fechinicejec > $dato[recafech]
        // Tomamos los registros _post
        $recaejec = $dato[recaejecpost];
        $recaredu = $dato[recaredupost];
        $recaordi = $dato[recaordipost];
        break;

      case 2: // $fechinicejec < $dato[recafech]
        // Tomamos los registros _ante
        $recaejec = $dato[recaejecante];
        $recaredu = $dato[recareduante];
        $recaordi = $dato[recaordiante];
        break;
    }

    if (substr ($estanotiprov,0,1) == 'N') {
      if (compfech (mostrarfecha($fecha), mostrarfecha($fechnotiprov)) == 2) {
        $reca = $recaejec;
      } else {
        if (compfech (mostrarfecha($fecha), mostrarfecha($fechfinaprov)) == 1) {
          $reca = $recaordi;
        } else {
          $reca = $recaredu;
        }
      }
    } else {
      $reca = $recaejec;
    }
  }

  return $reca;
}
?>
