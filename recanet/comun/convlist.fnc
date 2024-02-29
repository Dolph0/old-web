<?

function convlist ($clav,$valo,$tabl) {
  // Construye un vector asociativo y lo devuelve
  // $clav de la tabla $tabl que se quiere tomar como clave del 
  // vector, y $valo el valor que devolverá (también es campo de
  // $tabl) el vector para una clave dada. 

  $resp = sql ("select $clav,$valo from $tabl");
  while ( $regi = each( $resp ) ) { 
      $camp = $regi[ 1 ];
      $vect [$camp[0]] = $camp[1];
  }
  return $vect;
}

?>
