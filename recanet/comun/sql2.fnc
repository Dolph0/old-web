<?

// Interfaz sql para Postgresql
function sql( $peticion ) {

/* 
 Ejecuta una o más sentencias SQL contra la base de datos
 La función distingue entre:

 - Consulta (SELECT) : Se devuelve FALSE si no se encontró ningún registro
   que cumpla la condición. Si existe algún registro que la cumpla devolverá:

   * Si lo que se solicita es un campo y sólo se encuentra un registro (una 
     ocurrencia), devuelve el valor del campo en una variable del tipo 
     correspondiente. Ejemplo:

          $resp = sql("select codiusua from usua where codiusua='invitado'" );
          print("<br>codiusua = $resp <br>");

   * En el resto de los casos, devuelve un vector de vectores, siendo el segundo 
     asociativo, con cada elemento nombre de campo -> valor. 
     Ejemplo: 

          $resu = sql( "select codiusua,codizona from  usua ");
          if ( is_array( $resu ) )
            while ( $regi = each( $resu ) ) {
              $camp = $regi[ value ];
              print( "<br>codiusua= $camp[codiusua], codizona=$camp[codizona]<br>");
              // o bien codiusua= $regi[value][codiusua]
            }

         Si sabemos que sólo es devuelve registro se puede simplificar a 
          $regi = each( sql( "select codizona, codigrup from usua" ) );
          $codizona = $regi[value][codizona];
  
 - Actualización, inserción y eliminación: Todas las operaciones deben ir
   separadas por ";" y se ejecutarán como una única transacción. Si se encuentra 
   un SELECT en medio de dos operaciones, la función no devolverá ningún valor.
   Si todas las operaciones de la transacción se llevan a cabo correctamente se 
   hará un COMMIT para finalizar la transacción con todos los valores actualizados 
   y se devolverá TRUE. En caso, contrario se ejecutará un ROLLBACK, deshaciendo
   todos los cambios y se devolverá FALSE; en este caso, se pasará en la variable
   global $ERRORSQL el error que se cometió. Ejemplo:

       sql ("INSERT INTO usua(codiusua,codizona) VALUES ('10','global');
             SELECT * FROM usua;
             DELETE FROM usua WHERE codiusua='20';");

       // No se podrá comprobar los resultados devueltos del SELECT.

       sql ("INSERT INTO usua(codiusua,codizona) VALUES ('10','global');
             INSERT INTO usua(codiusua,codizona) VALUES ('10','semiglobal')");
       // Devolverá FALSE porque codiusua no se puede duplicar...
*/

  global $ERRORSQL;  // Variable donde se devuelve el mensaje de error

  // Datos de la conexión
  $base= "recanet";
  $usua= "web";

  // Se inicia ERRORSQL
  $ERRORSQL = "";


  if (!($idencone = pg_connect ("dbname=$base user=$usua")))
    return FALSE;
  
  $peticion = ltrim ($peticion);
  $peticion = rtrim ($peticion);
  // Se elimina el ultimo punto y coma si lo tuviera
  $peticion = ereg_replace (";$","",$peticion);
  #print "valor de peticion : $peticion";

  // Si se trata de una única petición
  if (!strstr ($peticion,";")) {
    $sentencias[0] = $peticion;
  }
  else {
    $peticion = ereg_replace ("; *[\n]$","",$peticion); 
    // Si no se trata de una única petición, se divide
    // en sentencias SQL
    $sentencias = explode (";",$peticion);
    if (!$sentencias[count($sentencias)-1]) {
      array_pop ($sentencias);
    }
  }

  // TRANSACCIÓN 
  @pg_exec ($idencone,"BEGIN;");

  $devolver = TRUE; 

  while ( $query = each ($sentencias)) { # Mientras haya sentencias que ejecutar...

    // Se le quita los espacios y saltos de linea al principio y al final
    $query[value] = trim ($query[value]);

    // Si se trata de un SELECT entonces 
    
    $resu = pg_exec ($idencone,"$query[value];"); 
    #@$resu=pg_exec ($idencone,"$query[value];");  //Esto es para que no se vea el mensaje

    if ($ERRORSQL = pg_ErrorMessage ()) { 
      # Ocurrio algo grave en el QUERY actual
      @pg_exec ($idencone, "ROLLBACK;");   // Fallo --> Rollback  
      #print "debug: ROLLBACK";
      pg_close ($idencone);  # Se termina la conexion
      // Tratamos los posibles errores de ERRORSQL

      // Fallo en una restriccion de campo
      if (ereg("CHECK constraint",$ERRORSQL)) {
        $ERRORSQL = "Sintaxis de campo erronea";
      }
 
      // Fallo por una clave duplicada
      if (($temp = ereg_replace ("^.*Cannot insert a duplicate key .*index [a-z]*_","",$ERRORSQL))!= $ERRORSQL) {
        $temp = ereg_replace ("_[a-z]*.*$","",$temp);
        $ERRORSQL = "Clave duplicada : $temp";
      }
      return FALSE;                        // Se sale del bucle
    }

    if (ereg ("^[sS][eE][lL][eE][cC][tT]",strtoupper($query[value]))) {
      // Si la sentencia comienza por SELECT
      if ( gettype($resu) == "boolean") { 
        $devolver = $resu;
      }
      else {
        $numeregi = pg_numrows ($resu);   // Registros encontrados
        $numecamp = pg_numfields ($resu); // Campos solicitados
        $devolver = "";
        if ( ( $numecamp == 1 ) and ($numeregi == 1)) {
          $campo = pg_fetch_row($resu,0);
          $devolver = $campo[0];
        } else { 
          $i=0;
          while( $i < $numeregi ) {
            $devolver[$i] = pg_fetch_array($resu,$i);
            $i++;
          } 
        }
      } 
    }
  }

  # Si se llego a este punto es que todo esta ok
  @pg_exec ($idencone,"COMMIT;"); // Ok -> Commit
  pg_close ($idencone);
  #print "debug: COMMIT";
  return $devolver;
}
?>
