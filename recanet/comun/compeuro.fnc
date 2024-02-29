
<?
function compeuro ( $dato ) {
  // Esta funcion recibe por parametros un numero decimal, y comprueba que sea correcto.
  // SOLO PERMITIMOS DOS DECIMALES
  // A diferencia de la funcion del mismo nombre de javascript, en ésta no se
  // admite la , como coma decimal. Solo el . porque la , y afue remplazada
  // por el . en aquella funcion en javascritp

  // Lo primero que hago es aplicar la expresion regular, y comprobar su formato.
  if ( !ereg('^[0-9]+\.?[0-9]{0,2}$', $dato) ) {
    return -1;
  }

  return $dato;
}
?>
