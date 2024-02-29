<?

function estaurlx () {
  // devuelve el nombre de archivo sin extensión de la página que se ejecuta actualmente
  $temp = getenv( "REQUEST_URI" );
  $temp2 = strrpos( $temp, "/" ) + 1;
  return( substr( $temp, $temp2, strpos( $temp, "." ) - $temp2 ) );
}

?>
