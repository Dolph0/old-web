<?
# Función para insertar/actualizar un registro
# - $tabl es el nombre de la tabla a actualizar
# - $dato es un asociativo cuyas claves son los nombres de los campos de la
#   tabla, y cuyos valores son los valores asociados a esos campos
# - $clav es una lista de los *nombres* de los campos que se usarán como clave
#   para saber si el registro existe ya o no (para insertar o actualizar)
# - $camp es una lista optativa de los campos que deben
#   insertarse/actualizarse, por si hay más en el vector de los que pertenecen
#   a la tabla.
function inseacturegi ($tabl, $dato, $clav, $camp = "") {
  global $ERRORSQL;
  # Primero buscamos si ya hay un registro en la tabla, según las claves
  # pasadas. Para ello, tenemos que construir la consulta.
  $query = "SELECT * FROM $tabl WHERE ";
  if (! is_array($clav))
    $clav = array ($clav);
  $claves = array();
  foreach ($clav as $c)
    array_push ($claves, "$c = '$dato[$c]'");
  $query .= join (' AND ', $claves);

  // print "La consulta de búsqueda de claves queda $query\n";

  # Antes de nada, si no nos han pasado unas claves especiales en $camp,
  # ponemos todas las que hay ya en $dato, para que el trato sea más homogéneo
  if (! is_array ($camp))
    $camp = array_keys ($dato);

  # Si lo encontramos, la orden tendrá que ser UPDATE. Si no, INSERT.
  if (sql ($query)) {
    $query = "UPDATE $tabl SET ";
    $valores = array();
    foreach ($camp as $c)
      array_push ($valores, "$c = '$dato[$c]'");
    $query .= join (', ', $valores);
    $query .= " WHERE ";
    $query .= join (' AND ', $claves);
  } else {
    $query = "INSERT INTO $tabl (";
    $campos  = array();
    $valores = array();
    foreach ($camp as $c) {
      array_push ($campos,  "$c");
      array_push ($valores, "'$dato[$c]'");
    }
    $query .= join (', ', $campos);
    $query .= ") VALUES (";
    $query .= join (', ', $valores);
    $query .= ")";
  }

  // print "La consulta final de inserción/actualización queda $query\n";

  sql ($query);
  return (! $ERRORSQL);
}
?>
