<?
// Función que, dado un fichero con una sóla línea, inserta saltos de línea
// según el número de caracteres por línea que se desea.
// El número de caracteres será el mismo para todas las líneas, aunque la última podría quedarse con menos.

// El parámetro $fich, es el fichero al que se le van a añadir los saltos de línea.
// El parametro $numecara indica el número de caracteres que tendrá la línea del fichero final.

function saltline( $fich, $numecara ) {

  // Abrimos el fichero en modo de lectura 
  $fd1 = fopen( $fich, "r" ); 
  // Abrimos un fichero temporal para escribir cada línea
  $fd2 = fopen( $fich."rust", "w" );

  // Variable que indica si se ha generado el nuevo fichero con saltos de línea.
  // Esta variable es útil para decidir si el nuevo fichero sustituye al original.
  $sust = 0;

  // Variable que indica si es la primera línea del fichero o no.
  // Es util para no imprimir un salto de línea al comienzo del fichero.
  $primline = 1;

  if ( $fd2 ) {
    while ( !feof( $fd1 ) ) {
      $line = "";  // Ristra con la línea a escribir en el nuevo fichero
      if ( $primline ) {
        $primline = 0;
      } else {
        $line .= "\r\n";
      }
      $line .= fgets( $fd1, $numecara );
      fputs( $fd2, $line );
    }
    fclose( $fd2 );
    $sust = 1;
  }
  fclose( $fd1 );

  if ( $sust ) {
    system( "mv ".$fich."rust ".$fich );
  }
}
?>
