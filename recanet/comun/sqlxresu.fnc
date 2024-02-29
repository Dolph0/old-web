<?

function sqlxresu( $erro ) {
  // Esta funcion se debe ejecutar cuando hubo error al realizar un INSERT O
  // UPDATE en la base de datos, para detectar el error que se produjo.

  // Los 3 errores posibles son: formato incorrecto de algun campo, campos
  // clave ya existentes en la tabla, o violación de la integridad referencial
  // entre tablas.

  // Si el formato de un campo es incorrecto, quiere decir que no cumplio las
  // expresiones regulares en javascript, y la unica manera posible es porque
  // desactivaron javascript del navegador. Lo ecahmos de la aplicacion.

  // En los otros 2 casos, se devuelve un vector de 2 entradas: el primer campo 
  // indica el error producido, y el segundo es la restriccion (constraint) de
  // la base de datos que genero el error.
  // En el script que llama a esta funcion, hay que tratar estos resultados
  // que la funcion devuelve en el vector para indicar al usuario cual fue el
  // error.

  if ( ereg( "Formato incorrecto", $erro ) ) {
    segu("Formato incorrecto de los datos: Intenta entrar desactivando JavaScript");
  }
  if ( ereg( "Clave duplicada", $erro ) ) {
    $reto[0] = 1;
    $reto[1] = substr( $erro, strrpos($erro," ")+1);
  }
  if ( ereg( "integridad referencial", $erro ) ) {
    $reto[0] = 2;
    $reto[1] = substr( $erro, strrpos($erro," ")+1);
  }
  print $reto[1];
  return $reto;

}

?>
