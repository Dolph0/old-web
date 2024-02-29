<?
// Funci�n que, dado un fichero con una s�la l�nea, inserta saltos de l�nea
// seg�n el n�mero de caracteres por l�nea que se desea.
// El n�mero de caracteres ser� el mismo para todas las l�neas, aunque la �ltima podr�a quedarse con menos.

// El par�metro $fich, es el fichero al que se le van a a�adir los saltos de l�nea.
// El parametro $numecara indica el n�mero de caracteres que tendr� la l�nea del fichero final.

function saltline( $fich, $numecara ) {

  // Abrimos el fichero en modo de lectura 
  $fd1 = fopen( $fich, "r" ); 
  // Abrimos un fichero temporal para escribir cada l�nea
  $fd2 = fopen( $fich."rust", "w" );

  // Variable que indica si se ha generado el nuevo fichero con saltos de l�nea.
  // Esta variable es �til para decidir si el nuevo fichero sustituye al original.
  $sust = 0;

  // Variable que indica si es la primera l�nea del fichero o no.
  // Es util para no imprimir un salto de l�nea al comienzo del fichero.
  $primline = 1;

  if ( $fd2 ) {
    while ( !feof( $fd1 ) ) {
      $line = "";  // Ristra con la l�nea a escribir en el nuevo fichero
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
