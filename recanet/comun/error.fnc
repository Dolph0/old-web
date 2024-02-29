<?

 #---------------------------------------------------------------------
 # Esta funcion se encarga de insertar incidencias en la base de datos 
 #
 # Entradas:
 #   $tipoobje -> Tipo de objeto en el que se ha producido el error 
 #   $resu     -> Campos "culpables" de la incidencia
 #   $codierro -> Codigo del error o una lista de errores 
 #   $usuamodi -> El usuario causante del "problema"
 #   $rutimodi -> Rutina que intento hacer la operacion 
 #
 # Salidas:
 #   True / false, si se ejecuto la rutina correctamente
 #---------------------------------------------------------------------
 function inseinci ($tipoobje, $resu, $codierro, $usuamodi, $rutimodi, $codiinci='') {
   # Inicializaciones
   $ejecuto = false;

   # Valores de hora/fecha
   list($usec, $time) = explode(" ",microtime());
   $usec = substr($usec, 2, 2);
 
   # Fecha y hora(con centesimas de segundo) del sistema 
   $fechmodi = date("Y-m-d", $time);
   $horamodi = date("H:i:s", $time).'.'.$usec;

   # Buscamos mediante el tipo de objeto la tabla en la que ponemos la incidencia 
   if (!$tabla = sql ("SELECT nombtabl FROM tipoobje WHERE tipoobje = '$tipoobje'")) {
     echo "Se desconoce la tabla de incidencias  para '$tipoobje'";
     exit;
   }

   # Condicion de busqueda en la tabla de incidencia correspondiente
   $where = " fechmodi='$fechmodi' AND horamodi='$horamodi' ";
   if ($usuamodi != '') {
     $where .= " AND usuamodi='$usuamodi'";
   }

   if ($rutimodi != '') {
     $where .= " AND rutimodi='$rutimodi'";
   }

   if (is_array ($resu)) {
     foreach ($resu as $campo => $valor) {
       if ($valor == ''){
          $where.= " AND ($campo ='$valor' OR $campo IS NULL)";
       }else{
       $where.= " AND $campo ='$valor' ";
       }
     }
   }

   # Consultamos el codigo de incidencia(codiinci) en la tabla especificada
   if (!$codiinci) {
     # Si no existe insertamos la incidencia:
     #   a) Construimos primero el fragmento de la consulta 
     #   b) Los campos que se van a insertar y sus valores

     $campos = "fechmodi, horamodi";
     $valo  = "'$fechmodi', '$horamodi'";
     if ($usuamodi != '') {
       $campos .= ",usuamodi";
       $valo .= ",'$usuamodi'";
     }
 
     if ($rutimodi != '') {
       $campos .= ",rutimodi";
       $valo .= ",'$rutimodi'";
     }

     if (is_array ($resu)) {
       foreach ($resu as $campo => $inse) {
         $campos.= ", $campo";
         $valo.= ", '$inse'";
       }
     }
     # Insertamos el registro
     sql ("INSERT INTO $tabla ($campos) VALUES ($valo)");
     # Obtenemos el codigo mediante la condicion 
     $codiinci = sql ("SELECT codiinci FROM $tabla WHERE $where");
   }
 
   if (!is_array ($codierro)) $codierro = array ("$codierro");
    
   # En este punto conocemos:
   #   1) El tipo de objeto que genero la incidencia = $tipoobje
   #   2) Codigo de la incidencia = $codiincia
   #   3) El codigo de error = $codierro
   # Podemos crear entonces la relación
    
   # Recorremos la lista de errores
   if (is_array ($codierro)) {
     foreach ($codierro as $t => $v) {
       # Si se inserta mantenemos la variable $ejecuto como verdadero 
       if (sql ("INSERT INTO incierro VALUES ('$tipoobje','$codiinci','$v')") && $v) {
         $ejecuto = true;
       } else {
         # En caso contrario la ponemos a 0 y salimos algo ha fallado
         $ejecuto = false;
         break;
       }
     }
   } else {
     $ejecuto = false;
   }
 
   # Resultado
   return $ejecuto;
 }


 #---------------------------------------------------------------------
 # Devuelve los errores que tiene una incidencia en forma de listado 
 # y con retorno de carro: 
 #                         ERROR 1
 #                         ERROR 2
 #                         ERROR 3 
 #                         ... 
 #
 # O un mensaje indicando para que tipo de objeto y codigo de incidencia 
 # no se han encontrado errores. 
 #
 # Entradas:
 #   $tipoobje -> Tipo de objeto en el que se ha producido el error 
 #   $codiinci -> Incidencia
 #
 # Salidas:
 #   Lista de errores o un mensaje de error si no se encuentra nada.
 #---------------------------------------------------------------------
 function mueserro ($tipoobje, $codiinci) {
   # Buscamos los errores
   $error = sql ("SELECT nomberro FROM tipoerro, incierro 
                   WHERE tipoerro.codierro = incierro.codierro 
                     AND tipoobje='$tipoobje' 
                     AND codiinci='$codiinci'"); 

   if (is_array ($error)) { 
     foreach ($error as $t => $v) { 
       $fallos.= " · ".$v[nomberro].".<br>\n"; 
     }

     return $fallos;
   }
    
   if ($error) {
     return " · ".$error."<br>\n";
   } else {
     return "No se han encontrado errores para $tipoobje:$codiinci <br>\n";
   }
 }

?>