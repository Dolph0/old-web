<?
// Ultimo cambio : 6-09-2001
// Interfaz sql para Postgresql
function sql( $peticion ) {
//para poder ver lo que contiene temporalmente:
//echo "<p><font color=blue>sql:<br>". $peticion ."</font><p>"; 



/* 
 Ejecuta una o m�s sentencias SQL contra la base de datos
 La funci�n distingue entre:

 - Consulta (SELECT) : Se devuelve FALSE si no se encontr� ning�n registro
   que cumpla la condici�n. Si existe alg�n registro que la cumpla devolver�:

   * Si lo que se solicita es un campo y s�lo se encuentra un registro (una 
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

         Si sabemos que s�lo es devuelve registro se puede simplificar a 
          $regi = each( sql( "select codizona, codigrup from usua" ) );
          $codizona = $regi[value][codizona];
  
 - Actualizaci�n, inserci�n y eliminaci�n: Todas las operaciones deben ir
   separadas por ";" y se ejecutar�n como una �nica transacci�n. 
   Si todas las operaciones de la transacci�n se llevan a cabo correctamente se 
   har� un COMMIT para finalizar la transacci�n con todos los valores actualizados 
   y se devolver� TRUE. En caso, contrario se ejecutar� un ROLLBACK, deshaciendo
   todos los cambios y se devolver� FALSE; en este caso, se pasar� en la variable
   global $ERRORSQL el error que se cometi�. Ejemplo:

       sql ("INSERT INTO usua(codiusua,codizona) VALUES ('10','global');
             SELECT * FROM usua;
             DELETE FROM usua WHERE codiusua='20';");

       // No se podr� comprobar los resultados devueltos del SELECT.

       sql ("INSERT INTO usua(codiusua,codizona) VALUES ('10','global');
             INSERT INTO usua(codiusua,codizona) VALUES ('10','semiglobal')");
       // Devolver� FALSE porque codiusua no se puede duplicar...

  -- N U E V O -- (06-09-2001) 

  Se puede combinar para que haya SELECT entre sentencias de actualizacion o 
  inserciones, como por ejemplo :

       sql ("INSERT INTO foo VALUES ('1','2');
             SELECT * FROM bar;
             DELETE FROM cpp;");

  Hay que tener en cuenta que, si hay varios SELECT dentro de la consulta, el 
  resultado que se devolvera es el del ultimo de ellos.

  $r = sql ("SELECT * FROM foo;
             INSERT INTO bar VALUES('2','4','5');
             SELECT * FROM bar;
             DELETE FROM foo;");

  $r tendra los valores de la tabla bar. 

  Tambien hay que tener en cuenta que, si dentro de la consulta hay algun 
  SELECT pero, luego se ejecuta otra sentencia que da un error, por el motivo
  que sea, la funcion devuelve error e ignora la consulta del select. 

  -- NUEVO -- (..-11-2001)
   
  La variable ERRORSQL detalla algunos errores producidos en la base de datos por el
  constraint de los campos..

*/

  global $ERRORSQL;  // Variable donde se devuelve el mensaje de error

  // Datos de la conexi�n
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

  // Si se trata de una �nica petici�n
  if (!strstr ($peticion,";")) {
    $sentencias[0] = $peticion;
  }
  else {
    $peticion = ereg_replace ("; *[\n]$","",$peticion); 
    // Si no se trata de una �nica petici�n, se divide
    // en sentencias SQL
    $sentencias = explode (";",$peticion);
    if (!$sentencias[count($sentencias)-1]) {
      array_pop ($sentencias);
    }
  }

  // TRANSACCI�N 
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
      if ( ereg( "CHECK constraint", $ERRORSQL ) ) {
        $ERRORSQL= substr ($ERRORSQL,52);
        $ERRORSQL= "Formato incorrecto: ". $ERRORSQL;
      }
      
      // Fallo por una clave duplicada
      if ( ereg( "Cannot insert a duplicate key", $ERRORSQL ) ) {
        $ERRORSQL = "Clave duplicada: ".substr( $ERRORSQL,(strrpos($ERRORSQL," ")+1) );
      }

      // Fallo en la integridad referencial entre tablas
      if ( ereg( "referential integrity violation", $ERRORSQL ) ) {
        $ERRORSQL = substr($ERRORSQL,8);
        $ERRORSQL = "Violaci�n de la integridad referencial: ".substr($ERRORSQL,0,strpos($ERRORSQL," "));
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
  pg_FreeResult( $resu );
  pg_close ($idencone);
  #print "debug: COMMIT";
  return $devolver;
}

function pg_primaryKey($table, $separator = ',') {
         $query = "SELECT ic.relname AS index_name, a.attname AS column_name,i.indisunique AS unique_key, i.indisprimary AS primary_key
          FROM pg_class bc, pg_class ic, pg_index i, pg_attribute a
          WHERE bc.oid = i.indrelid AND ic.oid = i.indexrelid
           AND (i.indkey[0] = a.attnum OR i.indkey[1] = a.attnum OR i.indkey[2] = a.attnum OR i.indkey[3] = a.attnum OR i.indkey[4] = a.attnum OR i.indkey[5] = a.attnum OR i.indkey[6] = a.attnum OR i.indkey[7] = a.attnum)
           AND a.attrelid = bc.oid AND bc.relname = '". $table . "'";
         $result = sql($query);
         if (!$result)
            return FALSE;
         $primaryKey = "";
         $first = TRUE;
         while ($reg = each($result)){
               $data = $reg[value];
               if ($data[unique_key] == 't' && $data[primary_key] == 't'){
                  if ($first) {
                     $primaryKey = $data[column_name];
                     $first = FALSE;
                  }else{
                     $primaryKey .= $separator . $data[column_name];
                  }
               }
         }
         if ($primaryKey == '')
            return FALSE;
         return $primaryKey;
}
?>
