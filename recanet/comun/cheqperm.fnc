<?

function cheqperm( $codioper, $sesicodiusua ) {
  // devuelve verdadero o falso dependiendo de si el usuario de la sesi�n tiene
  // concedido permiso sobre la operacion $codioper
  // $codioper es el codigo de la operacion a realizar
  return( sql( "select codiperm from usuaperm where codiperm='$codioper' 
    and codiusua='$sesicodiusua'" ) );
}

?>
