<?php
// Este script es llamado dando por hecho que se ha comprobado que los 
// tramos de las tarifas de un mismo subconcepto, no se solapan

// cate = busc            nume = oper
function calcsubc( $codisubc, $busc, $oper, $coditipo, $ejer, $oper2 = 1) {
  // calcula la subcuota de un subconcepto tributario, dado el tipo de cálculo

  // Filtro este parametro porque se usa en una division en uno de los case '':
  if (is_numeric ($oper2)) {
    if ($oper2 <=0) $oper2 = 1;
  } else {
    $oper2 = 1;
  }

  switch( $coditipo ) {
    case 'IFU': // 
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer'" );
      if ( !$subxcuot ) { return -1; }
      //echo "depurando subxcuot IFU = $subxcuot <br>";
      break;

    case 'ISC':
      $subxcuot = sql( "SELECT tari.base FROM tari WHERE 
                        tari.codisubc = $codisubc AND tari.cate = '$busc' AND tari.ejer = '$ejer'" );
      if ( !$subxcuot ) { return -1; }
      //echo "depurando subxcuot ISC = $subxcuot <br>";
      break;

    case 'IST':
      if ( $busc == "" ) $busc = 0;
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
		        AND ($busc BETWEEN tari.limiinfe AND tari.limisupe)");
      if ( !$subxcuot ) { return -1; }
      //echo "depurando subxcuot IST = $subxcuot <br>";
      break;

    case 'IST1':
      if ( $busc == "" ) $busc = 0;
      // busca el tramo que le corresponde
      $resp = sql( "SELECT tari.coditari, tari.base FROM tari 
                    WHERE tari.codisubc = $codisubc AND 
                    ($busc BETWEEN tari.limiinfe AND tari.limisupe) AND ejer = '$ejer'" );
      if ( is_array($resp) ) {
        $regi = each( $resp );
        $coditari = $regi[value][coditari];
        $subxcuot = $regi[value][base]; 

        // busca el primer tramo y que sea distinto del anterior
        $resp = sql( "SELECT tari.coditari, tari.base FROM tari WHERE tari.codisubc = $codisubc 
                      AND tari.coditari != $coditari AND tari.ejer = '$ejer' AND tari.limiinfe =
                      (SELECT MIN(tari.limiinfe) FROM tari WHERE tari.codisubc = $codisubc)");

        // Si el query anterior no devuelve nada, es porque
        // ya antes encontramos el primer tramo
        // Si devuelve algo, entra en el siguiente IF
        if ( is_array($resp) ){
          $regi = each( $resp );
          // acumula ambos valores
          $subxcuot = $subxcuot + $regi[value][base];
        }
      } else {
        // Devuelve error
        return -1; 
      }
      break;

    case 'ISCT1':
      // busca el tramo que le corresponde
      $resp = sql( "SELECT tari.coditari, tari.base FROM tari 
                    WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' AND 
                    tari.cate = '$busc' AND ($oper BETWEEN tari.limiinfe AND tari.limisupe)" );
      if ( is_array($resp) ) {
        $regi = each( $resp );
        $coditari = $regi[value][coditari];
        $subxcuot = $regi[value][base]; 

        // busca el primer tramo y que sea distinto del anterior
        $resp = sql( "SELECT tari.coditari, tari.base FROM tari WHERE tari.codisubc = $codisubc
                      AND tari.coditari != $coditari AND tari.ejer = '$ejer' AND tari.cate = '$busc' AND tari.limiinfe =
                      (SELECT MIN(tari.limiinfe) FROM tari WHERE tari.codisubc = $codisubc)");

        // Si el query anterior no devuelve nada, es porque
        // ya antes encontramos el primer tramo
        // Si devuelve algo, entra en el siguiente IF
        if ( is_array($resp) ){
          $regi = each( $resp );
          // acumula ambos valores
          $subxcuot = $subxcuot + $regi[value][base];
        }
      } else {
        // Devuelve error
        return -1; 
      }
      break;

    case 'ISTA':
      if ( $busc == "" ) $busc = 0;
      $subxcuot= sql( "SELECT sum(tari.base) FROM tari 
                       WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' AND $busc >= tari.limiinfe" );
      if ( !$subxcuot ) { return -1; }
      //echo "depurando subxcuot ISTA = $subxcuot <br>";
      break;

    case 'IMU':
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer'" );
      if ( !$subxcuot ) { return -1; }
      // Multiplico por el operador
      $subxcuot = $oper * $subxcuot;
      //echo "depurando subxcuot IMU = $subxcuot <br>";
      break;

    case 'MSC':
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
                        AND tari.cate = '$busc'" );
      if ( !$subxcuot ) { return -1; }
      // Multiplico por el operador
      $subxcuot = $oper * $subxcuot;
      //echo "depurando subxcuot MSC = $subxcuot <br>";
      break;

    case 'IMSCD':
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
                        AND tari.cate = '$busc'" );
      if ( !$subxcuot ) { return -1; }
      // Multiplico por los operadores
      // echo "depurando subxcuot IMSCD = $oper * $oper2 * $subxcuot <br>";      
      $subxcuot = $oper * $oper2 * $subxcuot;
      
      break;

    case 'MST':
      if ( $busc == "" ) $busc = 0;
      $subxcuot = sql( "SELECT tari.base FROM tari 
                        WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
			AND $busc BETWEEN tari.limiinfe AND tari.limisupe" );
      if ( !$subxcuot ) { return -1; }
      // Multiplico por el buscador
      $subxcuot = $busc * $subxcuot;
      //echo "depurando subxcuot MST = $subxcuot <br>";
      break;

    case 'PU':
      $subxcuot = sql( "SELECT tari.porc FROM tari WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer'" );
      if ( !$subxcuot ) { return -1; }
      // Aplico el porcentaje
      $subxcuot = $oper * $subxcuot * 0.01;
      //echo "depurando subxcuot PU = $subxcuot <br>";
      break;

    case 'PSC':
      $subxcuot = sql( "SELECT tari.porc FROM tari WHERE tari.codisubc = $codisubc 
                        AND tari.cate = '$busc' AND tari.ejer = '$ejer'" );
      if ( !$subxcuot ) { return -1; }
      // Aplico el porcentaje
      $subxcuot = $oper * $subxcuot * 0.01; 
      //echo "depurando subxcuot PSC = $subxcuot <br>";
      break;    

    case 'PST':
      if ( $busc == "" ) $busc = 0;
      $subxcuot = sql( "SELECT tari.porc FROM tari WHERE tari.codisubc = $codisubc  AND tari.ejer = '$ejer'
			AND '$busc' BETWEEN tari.limiinfe AND tari.limisupe" );
      if ( !$subxcuot ) { return -1; }
      // Aplico el porcentaje
      $subxcuot = $oper * $subxcuot * 0.01; 
      //echo "depurando subxcuot PST = $subxcuot <br>";
      break;    

    case 'PUM':
      $resp = sql( "SELECT tari.porc, tari.base FROM tari WHERE tari.codisubc = $codisubc AND tari.ejer = '$ejer'" );
      if ( is_array($resp) ) {
        $regi = each( $resp );
        $regi = $regi[value];
        // El operador al que se le aplica el porcentaje son euros. Hay que pasarlo a centimos
        $oper = euro2cent( $oper );
        // Aplico el porcentaje
        $subxcuot = $oper * $regi[porc] * 0.01;
        if ( $subxcuot < $regi[base] ) {
          // Si el resultado del porcentaje, es menor que el mínimo, se devuelve el valor mínimo.
          $subxcuot = $regi[base];
        }
      } else {
        $subxcuot = -1;
      }
      return $subxcuot;
      break;    

    case 'PSCM':
      $resp = sql( "SELECT tari.porc, tari.base FROM tari WHERE tari.codisubc = $codisubc
                    AND tari.cate = '$busc' AND tari.ejer = '$ejer'" );
      if ( is_array($resp) ) {
        $regi = each( $resp );
        $regi = $regi[value];
        // El operador al que se le aplica el porcentaje son euros. Hay que pasarlo a centimos
        $oper = euro2cent( $oper );
        // Aplico el porcentaje
        $subxcuot = $oper * $regi[porc] * 0.01;
        if ( $subxcuot < $regi[base] ) {
          // Si el resultado del porcentaje, es menor que el mínimo, se devuelve el valor mínimo.
          $subxcuot = $regi[base];
        }
      } else {
        $subxcuot = -1;
      }
      return $subxcuot;
      break;    

    case 'IET1':
      if ( $busc == "" ) $busc = 0;
      // Busca los tramos cuyos limites inferiores sean menores que el
      // valor de la variable $busc
      $resp = sql( "SELECT tari.coditari, tari.base, tari.limiinfe, tari.limisupe FROM tari 
                    WHERE $busc >= tari.limiinfe AND tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
                    ORDER BY tari.limiinfe" );
      if ( is_array($resp) ) {
       // $tramactu es una variable contadora que nos indica el 
       // tramo actual o iteracion del bucle
       $tramactu = 1;

       // Uso una nueva variable en la que pueda modificar el 
       // valor del numero $busc
       $numeactu = $busc;

       while ( $regi = each( $resp ) ) {

         $coditari = $regi[value][coditari];
         $limiinfe = $regi[value][limiinfe];
         $limisupe = $regi[value][limisupe];
         $base = $regi[value][base];

         //print "<br>".$coditari." & ".$limiinfe." & ".$limisupe." & ".$base;

         if ( $tramactu == 1 ) {
           $unid = $limisupe - $limiinfe;
 
           if ( $limiinfe > 0 ) $unid++;

           // Se inicializa la cuota con el valor del campo base
           // del primer tramo
           $subxcuot = $base;

         } else {
           $numeactu -= $unid;
           $unid = $limisupe - $limiinfe +1;

           if ( $numeactu > $unid ) {
             $subxcuot += $unid * $base;
           } else {
             $subxcuot += $numeactu * $base;
           }
         }

         $tramactu++;
       } // Fin del bucle

       //echo "depurando subxcuot IET1 = $subxcuot <br>";

     } else {
       // No hay ninguna tarifa para ese subconcepto de ese tipo de calculo
       // Devuelvo error
       return -1; 
     }
     break;


    case 'ISCET1':
      if ( $oper == "" ) $oper = 0;
      // Busca los tramos cuyos limites inferiores sean menores que el
      // valor de la variable $oper, y que pertenezcan a la categoria $busc
      $resp = sql( "SELECT tari.coditari, tari.base, tari.limiinfe, tari.limisupe FROM tari 
                    WHERE $oper >= tari.limiinfe AND tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
                    AND tari.cate = '$busc' ORDER BY tari.limiinfe" );
      if ( is_array($resp) ) {
        // $tramactu es una variable contadora que nos indica el 
        // tramo actual o iteracion del bucle
        $tramactu = 1;
 
        // Uso una nueva variable en la que pueda modificar el 
        // valor del numero $oper
        $numeactu = $oper;
 
        while ( $regi = each( $resp ) ) {
 
          $coditari = $regi[value][coditari];
          $limiinfe = $regi[value][limiinfe];
          $limisupe = $regi[value][limisupe];
          $base = $regi[value][base];
 
          //print "<br>".$coditari." & ".$limiinfe." & ".$limisupe." & ".$base;
 
          if ( $tramactu == 1 ) {
            $unid = $limisupe - $limiinfe;
            
            if ( $limiinfe > 0 ) $unid++;
 
            // Se inicializa la cuota con el valor del campo base
            // del primer tramo
            $subxcuot = $base;
          } else {
            $numeactu -= $unid;
            $unid = $limisupe - $limiinfe +1;
 
            if ( $numeactu > $unid ) {
              $subxcuot += $unid * $base;
            } else {
              $subxcuot += $numeactu * $base;
            }
          }
 
          $tramactu++;
        } // Fin del bucle
 
         //echo "depurando subxcuot ISCET1 = $subxcuot <br>";

      } else {
        // No hay ninguna tarifa para ese subconcepto de ese tipo de calculo
        // Devuelvo error
        return -1; 
      }
      break;

    case 'ISCET1E':
      $aux_oper = $oper; // Mantengo el valor
      
      if ($oper == "") { 
        $oper = 0; 
      } else {
        $oper = round ($oper / $oper2);
      }

      // Busca los tramos cuyos limites inferiores sean menores que el
      // valor de la variable $oper, y que pertenezcan a la categoria $busc
      $resp = sql ("SELECT tari.coditari, tari.base, tari.limiinfe, tari.limisupe 
                    FROM tari 
                    WHERE $oper >= tari.limiinfe AND tari.codisubc = $codisubc AND tari.ejer = '$ejer' 
                    AND tari.cate = '$busc' ORDER BY tari.limiinfe");
      if ( is_array($resp) ) {
        // $tramactu es una variable contadora que nos indica el 
        // tramo actual o iteracion del bucle
        $tramactu = 1;
 
        // Uso una nueva variable en la que pueda modificar el 
        // valor del numero $oper
        $numeactu = $oper;

        while ( $regi = each( $resp ) ) {
 
          $coditari = $regi[value][coditari];
          $limiinfe = $regi[value][limiinfe];
          $limisupe = $regi[value][limisupe];
          $base = $regi[value][base];
 
          //print "<br>".$coditari." & ".$limiinfe." & ".$limisupe." & ".$base;
 
          if ( $tramactu == 1 ) {
            $unid = $limisupe - $limiinfe;
            
            if ( $limiinfe > 0 ) $unid++;
 
            // Se inicializa la cuota con el valor del campo base
            // del primer tramo
            $subxcuot = $base;
          } else {
            $numeactu -= $unid;
            $unid = $limisupe - $limiinfe + 1;
 
            if ( $numeactu > $unid ) {
               $subxcuot += $unid * $base;
            } else {
              $subxcuot += $numeactu * $base;
            }
          }
 
          $tramactu++;
        } // Fin del bucle
    
        $subxcuot = $subxcuot * $oper2;
        //echo "depurando subxcuot ISCET1E = $subxcuot <br>";

      } else {
        // No hay ninguna tarifa para ese subconcepto de ese tipo de calculo
        // Devuelvo error
        return -1; 
      }
      break;
  }

  // Devuelve centimos de euro
  return $subxcuot;
}

function calccuot( $codiconc, $ejer, $codiayun, $busc, $oper, $exclsubc, $oper2 = '') {
  // dado un concepto tributario y un ejercicio calcula la cuota (en centimos de euros) 
  // que le corresponde en base a la suma de las subcuotas. 
  // Tambien se pasa por parametros, el ayuntamiento con el que tratamos.
  // El ejercicio se encuentra en la tabla de tarifas.
  // busc y oper son vectores que contienen los elementos para el calculo de los subconceptos de $codiconc.
  // exclsubc es el vector de subconceptos exlcuidos de la liquidacion.
  // La variable $mensaje informará del posible error que se pueda producir
  $mensaje= "";
  // $cuot acumula los resultados de los calculos de las subcuotas de cada subconcepto
  $cuot = 0;  // Al final indicara la cuota total

  // busca todos los subconceptos de ese concepto para hacer el sumatorio
  $query = "SELECT subxconc.codisubc, subxconc.nomb, subxconc.coditipo, subxconc.abre
            FROM subxconc, tipocalc, tari
            WHERE subxconc.codiconc = $codiconc 
              AND tipocalc.coditipo = subxconc.coditipo 
              AND tari.codisubc = subxconc.codisubc 
              AND tari.ejer = '$ejer'
            GROUP BY subxconc.codisubc, subxconc.nomb, subxconc.coditipo, subxconc.abre";
  $resu = sql( $query );

  if ( is_array( $resu ) ) {
      while ( $vect = each( $resu ) ) {
        $camp = $vect[ 'value' ];
        $codisubc = $camp['codisubc'];
        $nombsubc = $camp['nomb'];
        $abresubc = $camp['abre'];
        $tiposubc = $camp['coditipo'];

        // Comprobar si el subconcepto está excluido de la liquidacion
        if ( is_array( $exclsubc ) ) {
          if ( in_array( $codisubc, $exclsubc ) ) {
            continue;
          }
        }
        
        // Cálculo de la subcuota del subconcepto actual 
        $subxcuot = calcsubc( $codisubc, $busc[$codisubc], $oper[$codisubc], $tiposubc, $ejer, $oper2[$codisubc]);
        // Guardo la cuota de cada subconcepto, para devolverlas por separado
        $reto[$abresubc] = $subxcuot;

        if ( $subxcuot < 0 ){
          $mensaje = "$codisubc: No se ha encontrado una tarifa para el subconcepto $nombsubc del tipo de cálculo $tiposubc";
          // Salimos del bucle while, y detenemos la ejecucion
          break;
        } else {
          $cuot = $cuot + $subxcuot;
        }

      } // Fin del while que recorre la lista de subconceptos


  } else {
    $mensaje = "No existen subconceptos asociados a ese concepto tributario en ese ejercicio";
  }

  if ( !$mensaje ) {
    // No ha habido ningun error de busqueda en la base de datos
    // Ademas de las subcuotas de cada subconcepto, tambien devuelvo la cuota total, 
    // suma de cada una de esas subcuotas
    $reto['cuot'] = $cuot;
  } else {
    // Se muestra el error y se devuelve un numero negativo
    mens( $mensaje );
    $reto[cuot] = -1;
  }
  return ( $reto );
}