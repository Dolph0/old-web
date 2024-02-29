<?
  // Función que a partir de una fecha válida, devuelve el mes correspondiente.
  // Es empleada por la funcion prorrateo()
  function devumes( $fech ) {
    // La fecha debe tener un formato válido como 08-05-1975 ó 8-5-1975
    if ( $fech == "01-01-0001" || $fech == "" ) 
      // La fecha está en blanco
      return 0;
    // Compruebo si el dia viene expresado con uno o dos digitos, y despues lo mismo para el mes
    if ( ereg( "^[0-9][0-9]", $fech ) ) {
      // El dia esta expresado con dos digitos
      if ( ereg( "^[0-9][0-9].[0-9][0-9]", $fech ) ) {
        // El mes esta expresado con dos digitos
        $mes = substr( $fech, 3, 2 );
      } else {
        // El mes esta expresado con un digito
        $mes = substr( $fech, 3, 1 );
      }
    } else {
      // El dia esta expresado con un digito
      if ( ereg( "^[0-9].[0-9][0-9]", $fech ) ) {
        // El mes esta expresado con dos digitos
        $mes = substr( $fech, 2, 2 );
      } else {
        // El mes esta expresado con un digito
        $mes = substr( $fech, 2, 1 );
      }
    }
    // Convierto la ristra $mes en un valor entero
    $mes = intval($mes);
    return $mes;
  }


  // Función que a partir de una fecha válida, devuelve el año correspondiente.
  // Es empleada por la funcion prorrateo()
  function devuanio( $fech ) {
    // La fecha debe tener un formato válido como 08-05-1975 ó 8-5-1975
    if ( $fech == "01-01-0001" || $fech == "" ) {
      // La fecha está en blanco
      return 0;
    }
    list( $dia, $mes, $anio ) = split( '[/.-]', $fech );
    $anio = intval($anio);
    return $anio;
  }


  // Funcion que calcula las unidades a liquidar, cuando se trata de liquidacion por
  // fecha de alta y de baja (ambas), para una de las dos fechas (la de alta o la de baja).
  // Es llamada en la funcion prorrateo() cuando el tipo de fecha es por alta y por baja 
  // ($tipofech=="D"), y ademas, cuando el año de las fechas de prorrateo son iguales al
  // año contraido (ésto se comprueba antes de llamar a esta funcion).
  // Antes de llamar a esta funcion, se ha comprbado si la fecha estaba en blanco (01-01-0001)
  function altabaja( $mes, $tipopror ) {
    if ( $tipopror == "M" || $tipopror == "D") {
      if ( $mes == 1)    return 1;
      if ( $mes == 2)    return 2;
      if ( $mes == 3)    return 3;
      if ( $mes == 4)    return 4;
      if ( $mes == 5)    return 5;
      if ( $mes == 6)    return 6;
      if ( $mes == 7)    return 7;
      if ( $mes == 8)    return 8;
      if ( $mes == 9)    return 9;
      if ( $mes == 10)   return 10;
      if ( $mes == 11)   return 11;
      if ( $mes == 12)   return 12;
    }
    if ( $tipopror == "B" ) {
      if ( $mes == 1 || $mes == 2 )    return 1;
      if ( $mes == 3 || $mes == 4 )    return 2;
      if ( $mes == 5 || $mes == 6 )    return 3;
      if ( $mes == 7 || $mes == 8 )    return 4;
      if ( $mes == 9 || $mes == 10 )   return 5;
      if ( $mes == 11 || $mes == 12 )  return 6;
    }
    if ( $tipopror == "T" ) {
      if ( $mes == 1 || $mes == 2 || $mes == 3 )    return 1;
      if ( $mes == 4 || $mes == 5 || $mes == 6 )    return 2;
      if ( $mes == 7 || $mes == 8 || $mes == 9 )    return 3;
      if ( $mes == 10 || $mes == 11 || $mes == 12 ) return 4;
    }
    if ( $tipopror == "S" ) {
      if ( $mes == 1 || $mes == 2 || $mes == 3 || $mes == 4 || $mes == 5 || $mes == 6 ) 
        return 1;
      if ( $mes == 7 || $mes == 8 || $mes == 9 || $mes == 10 || $mes == 11 || $mes == 12 ) 
        return 2;
    }
  }

  // Funcion que calcula las unidades a liquidar segun el mes de la fecha de prorrateo,
  // el tipo de prorrateo y si es prorrateo por fecha de alta o de baja
  // Si se trata de prorrateo por fecha de alta o de baja, solo se comprueba el mes de una
  // sola fecha ($mes1). Si se trata de prorrateo por fecha de alta y de baja (ambos), se
  // comprobaría el mes de dos fechas, pero esto es tratado en la funcion altabaja().
  // Es llamada por la funcion prorrateo() cuando el tipo de fecha es por alta, o es por baja, 
  // pero no ambos a la vez, y ademas, cuando el año de la fecha de prorrateo es igual al 
  // año contraido (ésto se comprueba antes de llamar a esta funcion)
  function unidliqu( $tipopror, $tipofech, $mes ) {
    // Segun la liquidacion mensual
    if ( $tipopror == "M" || $tipopror == "D") { // Hay 12 meses en el año
      switch ( $mes ) {
        case 1:  // El mes es enero
          if     ( $tipofech == "A" ) { $unidactu = 12; } 
          elseif ( $tipofech == "B" ) { $unidactu = 1;  }
          break;
        case 2:  // El mes es febrero
          if     ( $tipofech == "A" ) { $unidactu = 11; } 
          elseif ( $tipofech == "B" ) { $unidactu = 2;  }
          break;
        case 3:  // El mes es marzo
          if     ( $tipofech == "A" ) { $unidactu = 10; } 
          elseif ( $tipofech == "B" ) { $unidactu = 3;  }
          break;
        case 4:  // El mes es abril
          if     ( $tipofech == "A" ) { $unidactu = 9;  }
          elseif ( $tipofech == "B" ) { $unidactu = 4; }
          break;
        case 5:  // El mes es mayo
          if     ( $tipofech == "A" ) { $unidactu = 8; } 
          elseif ( $tipofech == "B" ) { $unidactu = 5; }
          break;
        case 6:  // El mes es junio
          if     ( $tipofech == "A" ) { $unidactu = 7; }
          elseif ( $tipofech == "B" ) { $unidactu = 6; }
          break;
        case 7:   // El mes es julio
          if     ( $tipofech == "A" ) { $unidactu = 6; } 
          elseif ( $tipofech == "B" ) { $unidactu = 7; }
          break;
        case 8:  // El mes es agosto
          if     ( $tipofech == "A" ) { $unidactu = 5; }
          elseif ( $tipofech == "B" ) { $unidactu = 8; }
          break;
        case 9:  // El mes es septiembre
          if     ( $tipofech == "A" ) { $unidactu = 4; } 
          elseif ( $tipofech == "B" ) { $unidactu = 9; }
          break;
        case 10: // El mes es octubre 
          if     ( $tipofech == "A" ) { $unidactu = 3;  }
          elseif ( $tipofech == "B" ) { $unidactu = 10; }
          break;
        case 11: // El mes es noviembre
           if    ( $tipofech == "A" ) { $unidactu = 2;  } 
          elseif ( $tipofech == "B" ) { $unidactu = 11; }
          break;
        case 12: // El mes es diciembre  
          if     ( $tipofech == "A" ) { $unidactu = 1;  }
          elseif ( $tipofech == "B" ) { $unidactu = 12; }
          break;
      }
    }

    // Segun la liquidacion bimensual
    if ( $tipopror == "B" ) { // Hay 6 parejas de meses en el año
      switch ( $mes ) {
        case 1:    // El mes es enero o febrero
        case 2:
          if     ( $tipofech == "A" ) { $unidactu = 6; } 
          elseif ( $tipofech == "B" ) { $unidactu = 1; }
          break;
        case 3:    // El mes es marzo o abril 
        case 4:
          if     ( $tipofech == "A" ) { $unidactu = 5; }
          elseif ( $tipofech == "B" ) { $unidactu = 2; }
          break;
        case 5:    // El mes es mayo o junio
        case 6:
          if     ( $tipofech == "A" ) { $unidactu = 4; }
          elseif ( $tipofech == "B" ) { $unidactu = 3; }
          break;
        case 7: // El mes es julio o agosto
        case 8:
          if     ( $tipofech == "A" ) { $unidactu = 3; }
          elseif ( $tipofech == "B" ) { $unidactu = 4; }
          break;
        case 9: // El mes es septiembre u octubre 
        case 10:
          if     ( $tipofech == "A" ) { $unidactu = 2; }
          elseif ( $tipofech == "B" ) { $unidactu = 5; }
          break;
        case 11: // El mes es noviembre o diciembre
        case 12:
          if     ( $tipofech == "A" ) { $unidactu = 1; }
          elseif ( $tipofech == "B" ) { $unidactu = 6; }
          break;
      }
    }

    // Segun la liquidacion trimestral
    if ( $tipopror == "T" ) { // Hay 4 trimestres en el año
      switch ( $mes ) {
        case 1:    // El mes esta entre enero y marzo
        case 2:
        case 3:
          if     ( $tipofech == "A" ) { $unidactu = 4; }
          elseif ( $tipofech == "B" ) { $unidactu = 1; }
          break;
        case 4:    // El mes esta entre abril y junio
        case 5:
        case 6:
          if     ( $tipofech == "A" ) { $unidactu = 3; }
          elseif ( $tipofech == "B" ) { $unidactu = 2; }
          break;
        case 7:    // El mes esta entre julio y septiembre
        case 8:
        case 9:
          if     ( $tipofech == "A" ) { $unidactu = 2; }
          elseif ( $tipofech == "B" ) { $unidactu = 3; }
          break;
        case 10: // El mes esta entre octubre y diciembre
        case 11:
        case 12:
          if     ( $tipofech == "A" ) { $unidactu = 1; }
          elseif ( $tipofech == "B" ) { $unidactu = 4; }
          break;
      }
    }

    // Segun la liquidacion semestral
    if ( $tipopror == "S" ) { // Hay 2 semetres en el año
      if ( $mes <= 6 ) {  // El mes esta entre enero y junio
        if     ( $tipofech == "A" ) { $unidactu = 2; }
        elseif ( $tipofech == "B" ) { $unidactu = 1; }
      } else {    // El mes esta entre julio y diciembre
        if     ( $tipofech == "A" ) { $unidactu = 1; }
        elseif ( $tipofech == "B" ) { $unidactu = 2; }
      }
    }

    return $unidactu;
  }


  
  // Funcion que calcula el prorrateo a aplicar para el calculo de la deuda
  // Se requiere el tipo de prorrateo $tipopror, la fecha de alta y de baja $fechalta y $fechbaja, 
  // si el prorrateo es por fecha de alta o por fecha de baja o por los dos $tipofech (solo 
  // toma los valores "A","B" y "D"), y el año contraído de la deuda.
  // Se supone que son fechas validas, de formato 01-01-2001 o 1-1-2001 y que los separadores de 
  // la fecha pueden ser '-' '/' '.' . Si no, es un error.
  // La variable $unidtota indica el numero total de unidades de prorrateo que hay en el año.
  // $unitota depende de $tipopror
  // La variable $unidactu indica el numero de unidades de prorrateo en el que se encuentre el mes 
  // de la fecha, contando desde el dia de la fecha hasta final de año.
  // El parametro de entrada anio es el año contraido de la deuda.
  // Si se produjo algun error, devuelve -1. Si no, un valor en el rango (0,1]
  // Ademas, si segun las fechas de entrada, se deduce que no se debe liquidar, por ejemplo,
  // cuando es un prorrateo por fecha de baja, y la fecha de baja es anterior al año contraido, 
  // entonces devuelve 0.
  function prorrateo( $tipopror, $fechalta, $fechbaja, $tipofech, $anio ) {

    if ( ( $tipopror != "N" ) && ( $tipopror != "D" ) && ( $tipopror != "M" ) && ( $tipopror != "B" ) && ( $tipopror != "T" ) && ( $tipopror != "S" ) ) 
      return -1;
      
    if ( ( $tipofech != "A" ) && ( $tipofech != "B" ) && ( $tipofech != "D" ) )
      return -1;

    if ( ( $fechalta != "" && !cheqfech( $fechalta ) ) || 
         ( $fechbaja != "" && !cheqfech( $fechbaja ) ) )
      return -1;

    if ( $anio == "" )
      return -1;
      
    if ( $tipopror == "N" ) {
      // Que no se aplique prorrateo equivale a que el valor del prorrateo es 1
      return 1;

    } else {
      // Obtengo el numero de unidades totales de prorrateo en un año
      if ( $tipopror == "M" ||  $tipopror == "D" ) $unidtota = 12;
      if ( $tipopror == "B" ) $unidtota = 6;
      if ( $tipopror == "T" ) $unidtota = 4;
      if ( $tipopror == "S" ) $unidtota = 2;

      // Obtengo el numero de unidades de prorrateo en el que se encuentra la fecha
      // Depende de si es un prorrateo por fecha de alta, baja o ambas.
      
      // Separo el proceso en dos partes: cuando es por fecha de alta o por fecha de baja,
      // y cuando se trata de prorrateo por fecha de alta y de baja (ambas a la vez)
      if ( $tipofech == "A" || $tipofech == "B" ) {
        // El numero de meses a liquidar se obtiene a partir de una fecha solamente, 
        // la fecha de alta o la de baja, segun sea el caso.

        // Lo primero que se hace es comprobar si la fecha de prorrateo esta 
        // en blanco (01-01-0001)
        if ( $tipofech == "A" ) {
          if ( $fechalta == '01-01-0001' || $fechalta == '' ) {
            // Suele ocurrir en objetos antiguos que no tienen asignada la fecha de inicio.
            // Se liquida el año completo.
            return 1;
          }
          $fech = $fechalta;
        } else {
          if ( $fechbaja == '01-01-0001' || $fechbaja == '' ) {
            // No suele ocurrir. No se liquida porque se pretende una liqu. directa por baja,
            // y aun no se ha dado de baja el objeto (no tiene asiganda la fecha)
            return 0;
          }
          $fech = $fechbaja;
        }


        // Obtengo el mes en el que se encuentra la fecha
        $mes = devumes( $fech );
        // Tambien obtengo el año de la fecha de prorrateo, para determinar si es anterior, 
        // igual o posterior que el año contraido.
        $aniopror = devuanio( $fech );
        // Ahora calculo el numero de unidades en que esta la fecha, segun el mes, el tipo de prorrateo
        // y segun sea prorrateo por fecha de alta o por fecha de baja.
        // Pero tambien hay que considerar si la fecha de prorrateo es anterior,posterior o del mismo
        // año que el año contraido
        if ( $aniopror < $anio ) {
          if ( $tipofech == "A" ) {
            $unidactu = $unidtota;  // El año completo
          } else {
            // No se liquida porque la fecha de baja es anterior al año contraido.
            // Es una baja anterior
            return 0;
          }
        } elseif ( $aniopror == $anio ) {
          //$unidactu = unidliqu( $tipopror, $tipofech, $fech, $anio );
          $unidactu = unidliqu( $tipopror, $tipofech, $mes );
        } elseif ( $aniopror > $anio ) {
          if ( $tipofech == "A" ) {
            // No se liquida porque el alta del objeto es posterior al contraido
            return 0;
          } else {
            // El año completo porque la baja es en un año posterior al contraido
            $unidactu = $unidtota;
          }
        }



      } elseif ( $tipofech == "D" ) {
        // El numero de meses a liquidar se obtiene a partir de dos fechas, la de alta y la de baja.

        // Lo primero es comprobar si el objeto no se debe liquidar, porque la fecha de alta o la
        // baja sea posterior o anterior, respectivamente, al año contraido de la deuda.
        // Obtengo el año de las fechas, para compararlo con el de las fechas de prorrateo.
        $anioalta = devuanio( $fechalta );
        if ( $anioalta > $anio ) {
          return 0;  // No se liquida. Se dio de alta despues del año contraido
        }
        if ( ( $fechbaja != '01-01-0001' ) && ( $fechbaja != '' ) ) {
          // Hay que comprobar que la fecha de baja no esté en blanco, porque si no, la fecha '01-01-0001'
          // sería menor que al año actual, y devolvería 0, con lo que la liquidación da 0.
          $aniobaja = devuanio( $fechbaja );
          if ( $aniobaja < $anio ) {
            return 0;  // No se liquida. Se dio de baja antes del año contraido
          }
        }

        // A continuacion, calculo las unidades iniciales y finales de prorrateo
        // El primer paso que se hace es comprobar si la/-s fecha/-s de prorrateo esta/-n
        // en blanco (01-01-0001)

        // Primero calculo las unidades iniciales
        if ( $fechalta == '01-01-0001' || $fechalta == '' ) {
          $unid1 = 1;               
        } else {                            
          // Primero calculo el numero de unidades inicial. Necesito comprobar el mes de la fecha.
          $mes1 = devumes( $fechalta );                           
          if ( $anioalta == $anio ) {
            // Calculo el numero de unidades en que esta la fecha, segun el mes y el tipo de prorrateo
            $unid1 = altabaja( $mes1, $tipopror );
          } elseif ( $anioalta < $anio ) {
            // Se liquida el año completo, a falta de saber cuando es la baja (en el mismo año que el 
            // contraido o en años posteriores)
            $unid1 = 1;
          }
        }

        // Ahora obtengo las unidades finales
        if ( $fechbaja == '01-01-0001' || $fechbaja == '' ) {
          $unid2 = $unidtota;
        } else {                            
          // Ahora calculo el numero de unidades final. Necesito comprobar el mes de la fecha.
          $mes2 = devumes( $fechbaja );
          if ( $aniobaja == $anio ) {
            // Calculo el numero de unidades en que esta la fecha, segun el mes y el tipo de prorrateo
            $unid2 = altabaja( $mes2, $tipopror );
          } elseif ( $aniobaja > $anio ) {
            // Se liquida el año completo, a falta de saber cuando es el alta (en el mismo año que el 
            // contraido o en años anteriores)
            $unid2 = $unidtota;
          }
        }

        // La formula para calcular las unidades a liquidar:
        $unidactu = $unid2 - $unid1 + 1;

        if ($tipopror == "D" && $unidactu = 1){ 
          $numdias = round(NumeDias(Guardarfecha($fechalta), Guardarfecha($fechbaja))) + 1;
          if ($numdias < 30) {
            if (($anio % 4 == 0) && ($anio % 100 != 0) || ($anio % 400 == 0)){
              $unidtota = 366;
            } else { 
              $unidtota = 365;
            }
            $unidactu = $numdias;
          }
        }
      }

      return ($unidactu / $unidtota);
    }
  }
?>
