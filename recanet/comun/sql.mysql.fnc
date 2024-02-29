<?
function sql( $sql ) {
  // Devuelve el resultado de ejecutar la sentencia sql que se le pasa por parametros
  // contra la base de datos MySQL
  // Valores que devolvera: 
  //   1 (verdadero)
  //     si es una operacion de UPDATE, DELETE o INSERT y no se producen errores
  //   nada (falso)
  //     si se produce algun error cuando se intenta realizar el query, por ejemplo, 
  //     cuando la consulta tiene errores de sintaxis, o cuando se produce un error 
  //     relativo a la base de datos como clave duplicada.
  //     En estos casos, lo que hace es mostrar por pantalla el error de MySQL
  // 
  //   un sólo valor de una sola ocurrencia de registro
  //    Es la típica consulta para obtener la descripción dado un código.
  //    Devuelve 
  //      * el valor del query en una variable, del tipo correspondiente si lo encuentra
  //      API
  //        $resp = sql("select codiusua from usua where codiusua='invitado'" );
  //        print("<br>codiusua = $resp <br>");
  //      * nada si el query no lo encuentra, tiene errores de sintaxis o devuelve algo más 
  //        que un valor ( más de un campo o más de una ocurrencia de registro o ambas cosas )
  //
  //   uno o más campos de una o más ocurrencias de registro
  //    Por ejemplo los valores de varias fichas clientes
  //    Devuelve
  //      * un vector de vectores, siendo el segundo asociativo, 
  //        con cada elemento nombre de campo -> valor
  //      API
  //        $resu = sql( "select codiusua,codiambi from  usua ");
  //        if ( is_array( $resu ) )
  //          while ( $regi = each( $resu ) ) {
  //            $camp = $regi[ value ];
  //            print( "<br>codiusua= $camp[codiusua], codiambi=$camp[codiambi]<br>" );
  //            // o bien codiusua= $regi[value][codiusua]
  //          }
  //        Si sabemos que solo es un registro se puede simplificar a 
  //         $regi = each( sql( "select codiambi from usua" ) );
  //         $codiambi = $regi[value][codiambi];
  //      * nada si el query no devuelve ninguna ocurrencia de registro
  //
  //      * la función dará error si el query tiene error de sintaxis y abortará


  // Por fin el codigo

  /* Informacion necesaria para conectarnos a una base de datos MySQL */
  $usuario="root";
  $clave="recanet";
  $basedato= "recanet"; // seleciona la base de datos

  // abre una conexion con la base de datos
  $idencone = mysql_connect ("localhost", $usuario, $clave);
  if ( !$resu = mysql_db_query( $basedato, $sql, $idencone ) ) // lanza la consulta
    // Se produce un error al lanzar la consulta sql
    echo "ERROR: ".mysql_error();
  mysql_close( $idencone ); // cierra la conexion con la base de datos

  if ( gettype( $resu ) == "boolean") {  
    $devolver = $resu;
  } else {
    $numeregi = mysql_num_rows ( $resu ); // numero de registros encontrados
    $numecamp = mysql_num_fields( $resu ); // numero de campos solicitados
    if ( ( $numecamp == 1 ) and ( $numeregi == 1 ) ) {
      $campo = mysql_fetch_row( $resu );
      $devolver = $campo[0];
    } else { 
      $i=0;
      while( $regi = mysql_fetch_array( $resu ) ) {
        $devolver[ $i ] = $regi;
        $i++;
      } 
    }
  } 
  // depurar esto que no va
  // if ( $resu ) mysql_free_result( $resu );
  return( $devolver );
}

?>
