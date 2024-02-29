<?
function redocuot( $vect ) {
  // Funcion que redondea las cuotas (o la deuda) debido a que las sumas de las cuotas
  // no coincide con el total de la deuda, por culpa del redondeo.

  // Total de la suma de las cuotas
  $totacuot = 0;
  $contcuot = 0; // Contador de subcuotas
  foreach( $vect as $nomb => $valo ) {
    if ( ( $nomb != 'cuot' ) && ( $nomb != 'deud' ) ) {
      // El vector que recibe por parametros, ademas de las subcuotas, tambien contiene
      // la deuda en la posicion "deud" y la cuota total en la posicion "cuot". Pero no
      // los tengo en cuenta porque solo me interesan las subcuotas.
      $totacuot += $valo;
      $contcuot++;
      // Guardo los nombres de la cuotas para el caso de que tuviera que indexarlas posteriormente,
      // y modificar su valor.
      $nombcuot[$contcuot] = $nomb;
    }
  }

  // Compruebo si hay diferencias entre la suma de las subcuotas, y la deuda total
  if ( $totacuot != $vect[deud] ) {
    // Pueden producirse dos casos: errores de redondeo de 1 centimo, o superiores por
    // bonificaciones o prorrateo o por ingreso a cuenta.
    // Primero compruebo si hay agun error de redondeo
    if ( abs( $vect[deud] - $totacuot ) == 1 ) {
      if ( $vect[deud] > $totacuot ) {
        // Incremento 1 centimo una subcuota
        $nom1 = $nombcuot[1];  // Nombre de la primera subcuota
        $vect[$nom1]++;
      } else {
        // Incremento 1 centimo la deuda
        $vect[deud] ++;
      }
    } else {
      // Si la diferencia es superior a 1 centimo, no es por culpa del redondeo.
      // Puede ser por prorrateo o bonificaciones o por ingreso a cuenta.

      if ( $contcuot == 1 ) {
        // Como solo hay una unica cuota, le asigno el valor reducido de la deuda
        $nom1 = $nombcuot[1];
        $vect[$nom1] = $vect[deud];

      } else {
        // Hay mas de una cuota.
        // La deuda se ha visto reducidad en un porcentaje. Vamos a calcular dicho porcentaje, para
        // aplicarselo a las subcuotas.
        if ($totacuot > 0) { 
          $porc = ( $vect[deud] * 100 ) / $totacuot;
        } else {
          $porc = 0; 
        }
        
        // Recorrer las subcuotas y aplicarle el mismo porcentaje en que se redujo la deuda
        // Necesito un contador de las cuotas a las que ya le he aplicado el porcentaje, y
        // el importe ya reasignado a las cuotas. Esto lo uso para que el valor de la ultima cuota
        // sea la diferencia de la deuda, y de las subcuotas ya reasignadas.
        $cont = 0;
        $impo = 0;
        foreach( $vect as $nomb => $valo ) {
          if ( ( $nomb != 'cuot' ) && ( $nomb != 'deud' ) ) {
            if ( $cont == ( $contcuot - 1 ) ) {
              $vect[$nomb] = $vect[deud] - $impo;
            } else {
              $vect[$nomb] = $valo * $porc * 0.01;
            }
            // Redondeo la nueva cuota
            $vect[$nomb] = euro2cent( $vect[$nomb] * 0.01 );
            $cont++;
            $impo += $vect[$nomb];
          }
        }
      }
    }
  }

  // Devuelvo el vector que recibe como parametro de entrada.
  return $vect;
}
?>
