<?

function estaurlx () {
  // devuelve el nombre de archivo sin extensi�n de la p�gina que se ejecuta actualmente
  $temp = getenv( "REQUEST_URI" );
  $temp2 = strrpos( $temp, "/" ) + 1;
  return( substr( $temp, $temp2, strpos( $temp, "." ) - $temp2 ) );
}

?>
