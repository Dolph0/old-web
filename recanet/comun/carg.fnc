<?
  # Funciones que tienen relaci�n con los cargos (consultan sus propiedades,
  # las interpretan, etc.)

function ImpoDemo( $estacont, $fechestacont, $fechfinavolu,
                   $fechfinaprov, $deud, $fech ) {

  #  include "../comun/fecha.fnc";

  # calcula los intereses de demora
  # Par�metros de entrada
  #
  # estacont     : de carg.estacont
  #                Durante el periodo de c�lculo debe ser pte. de cobro
  # fechestacont : de carg.fechestacont
  #                Fecha en q cambi� el estado contable
  #                  normalmente a # pte. cobro
  #                Formato aaaammdd
  # fechfinavolu : de carg.fechfinavolu
  #                Fecha a partir de la que empieza el c�lculo
  #                Formato aaaammdd
  # fechfinaprov : de carg.fechfinaprov
  #                Tiene que ser anterior a la fecha de c�lculo
  #                Formato aaaammdd
  # deud         : de carg.deud
  # fech         : fecha en la que se quiere conocer el c�lculo de I.de demora
  #
  # Salida: entero con el c�lculo del importe de demora


  # Sustituci�n simple para poder pasar los par�metros de fecha tal cual de la
  # base de datos (con guiones)
  $fechestacont = preg_replace ('/-/', '', $fechestacont);
  $fechfinavolu = preg_replace ('/-/', '', $fechfinavolu);
  $fechfinaprov = preg_replace ('/-/', '', $fechfinaprov);
  $fech         = preg_replace ('/-/', '', $fech);

  #
  # Si no est� en ejecutiva en la fecha del c�lculo
  #
  if ( ( $fechfinaprov == "" ) or ( $fechfinaprov == "00010101")
    or ( $fech <= $fechfinaprov ) ) $impodemo = 0;
  else {
    # calcula entre q fechas hacer el c�lculo
    # ambos d�as inclusive
    # para el inicio toma el d�a siguiente
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
          # divide en periodo incluidos en el a�o natural
          # fecha de inicio del primer periodo
          $fechperiinic = $fechinic;
          # a�o del primer periodo
          $anioperi = substr( $fechperiinic, 0, 4);
          # a�o del �ltimo periodo
          $aniofina = substr( $fechfina, 0, 4);
          $impodemo = 0;
          // print "depurando antes del bucle <br>";

          # Antes que nada, recogemos de la base de datos los valores del
          # inter�s de demora que necesitar� impodemoperi, para pas�rselos como
          # par�metro
          # NOTA: "anio" es para forzar a que sql devuelve *siempre* una matriz
          $query = "SELECT anio, intedemo FROM valoanua WHERE anio >= $anioperi AND anio <= $aniofina ORDER BY anio";
          $resu = sql ($query);
          if (! is_array ($resu)) {
            print "�ERROR! NO SE ENCUENTRAN LOS INTERESES DE DEMORA ENTRE LOS A�OS $anioperi Y $aniofina<br>\n";
            exit;
          }

          while ( $anioperi <= $aniofina ) {
            $dato = each ($resu);
            $dato = $dato[value];
            // print "depurando a�operi=$anioperi, a�ofina=$aniofina,
              // fechinic = $fechinic, fechfina=$fechfina <br>";
            # si no es el �ltimo periodo coge el �ltimo d�a del a�o
            if ( $anioperi < $aniofina ) $fechperifina = $anioperi."1231";
            else $fechperifina = $fechfina;
            $impodemo += ImpoDemoPeri( $fechperiinic, $fechperifina, $deud, $dato[intedemo] );
            // print "impodemo = $impodemo <br>";
            # si es el �ltimo periodo sale
            if ( $anioperi == $aniofina ) $anioperi = $aniofina +1;
            else {
              # fecha de inicio del periodo
              $fechperiinic = SumaDias( $fechperifina, 1 );
              # a�o del periodo
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
  # est� dentro de un a�o natural
  # S�lo es llamada por la funci�n ImpoDemo
  #
  # Par�metros de entrada
  #
  #  fechinic fecha que indica el d�a de inicio del periodo de c�lculo.
  #     Este d�a se incluye en el c�lculo
  #     Formato aaaammdd
  #
  #  fechfina fecha que indica el d�a de fin del periodo de c�lculo.
  #     Este d�a se incluye en el c�lculo
  #     Formato aaaammdd
  #
  #  deud importe sobre el que calcular el inter�s

  # Obtiene el a�o
  $anio = (int)substr( $fechinic, 0, 4 );

  # Verifica que ambas fechas sean del mismo a�o natural
  if ( $anio != (int)substr( $fechfina, 0, 4 ) ) $impodemo = -1 ;
  else {

    # C�lcula el n�mero de d�as
    $numedias = NumeDias( $fechinic, $fechfina ) ;
    if ( $numedias == -1 ) $impodemo = -1;
    else {
      # incluye ambos l�mites
      $numedias++;

      # Si hay errores de formato o los l�mites est�n cruzados o fuera de
      # rango
      if ( $numedias == -1 ) $impodemo = -1 ;
      else {

        # Obtiene el a�o
        $anio = (int)substr( $fechinic, 0, 4 );

        # Calcula si es bisiesto
        if ( checkdate( 2, 29, $anio ) ) $bisi = 1;
        else $bisi = 0;

        # Busca el tipo de inter�s por demora del a�o
        if ($tipointe == 'nada')      # Compatibilidad con versi�n anterior
          $tipointe = sql( "select intedemo from valoanua where anio = $anio" );
        # Si no est� se pone a 0 porque a -1 confundir�a el c�lculo
        # V2. incidencias

        # C�lcula el importe
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
# v�a *efectiva*, el porcentaje de recargo, el recasino o recanono (el que se
# aplique) del a�o del que se quiere calcular el porcentaje, el tipo de
# objeto, el modo de liquidaci�n, la fecha de pago (normalmente, el mismo
# d�a) y la fecha de final de periodo voluntario de cobro.
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
# Devuelve la v�a efectiva de un cargo (no es necesariamente la que est�
# guardada en la B.D., que por cierto se da como par�metro en $pericont)
# El formato devuelto por la funci�n es para imprimir ('VOLUNTARIA' o
# 'EJECUTIVA'). Devolver� 'N/A' si pericont es incorrecto.
#
# Entradas:
#   $estacont     => Estado contable
#   $pericont     => Periodo contable
#   $fechfinavolu => Fecha final plazo de voluntaria (YYYY-MM-DD)
#   $estanotivolu => Estado notificacion de voluntaria
#   $fecha        => Fecha sobre la que trabajamos (YYYY-MM-DD), por
#                    omisi�n es hoy.
#
# Salidas:
#   "VOLUNTARIA" � "EJECUTIVA".
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
      // Si a�n no ha sido notificado, se mantiene en periodo voluntario.
      return 'VOLUNTARIA';
    } else {
      return ($fecha <= $fechfinavolu)?'VOLUNTARIA':'EJECUTIVA';
    }
  }
}



// Funcion que devuelve el valor de recasino o recanono de la tabla valoanua,
// seg�n el estado de notificaci�n y las fechas de notificaci�n y estado.
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
// Esta funci�n devuelve el porcentaje de recargo.
//
// Entradas:
//   $fechinicejec => Fecha inicio periodo ejecutivo (AAAA-MM-DD) (fechfinavolu + 1)
//   $fechnotiprov => Fecha notificaci�n de la providencia de apremio (AAAA-MM-DD)
//   $fechfinaproc => Fecha fin periodo ejecutiva (AAAA-MM-DD)
//   $fecha        => Fecha sobre la que trabajamos (AAAA-MM-DD)
//   $estanotiprov => Tipo de notificaci�n
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
