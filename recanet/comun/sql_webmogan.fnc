<?php
// Interfaz sql para Postgresql con AdoDB
include "../../adodb-5.20.14/adodb.inc.php";
include "../../adodb-5.20.14/adodb-errorhandler.inc.php";

//------------------------------------------
// Ejecuta el query indicado
//
// Entradas:
//   $peticion => Query.
//   $rows     => Numero de filas afectas.
//                Este campo solo se usara en los casos
//               en que no se ejecuta un SELECT, es decir,
//               UPDATE, DELETE o INSERT. En estos casos,
//               siempre se devolvia un 1.
//-------------------------------------------
function sql ($peticion, $rows = false ) {
  global $db;
    
  $mtime = explode(" ", microtime()); 
  $tiempoinicial = $mtime[1] + $mtime[0];   
  
  // Filtro
  if (trim ($peticion) == null) return false;

  // Realizamos la conexión a la base de datos.
  if (!isset($db) || ($db === false)) {    
    $db = NewADOConnection('postgres9');
    $db->Connect("localhost", "webmogan_user", "m0gan%data.base","webmogan_data");
  }   

  //Especificamos que queremos un array indexado por el campo. 
  $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

  //Ejecutamos la consulta. 
  $result = $db->Execute("$peticion");
      
  //Comprobamos si no hay errores
  if ($result == false) die($db->ErrorMsg()); 

  
  $mtime = explode(" ",microtime());
  $tiempofinal = $mtime[1] + $mtime[0]; 
  $tiempototal = (float)$tiempofinal - $tiempoinicial;    
  
  if ($tiempototal > 20){
    //error_log (date("d-m-Y H:i:s").'|Tiempo: '.$tiempototal.' segundos|Script:'.$_SERVER["REQUEST_URI"].'|Query:'.$peticion." \n", 3, "/var/log/querys-pesados.log");
  }   
  
  //Vemos si se trata de un select
  if ($result->FieldCount() == 0){
      if ($rows) return $db->Affected_Rows();
      else return 1; 
  }else{
     //Comprobamos si el resultado es vacío
     if ($result->RecordCount()==0){      
        return "";
     }else{
        //Comprobamos si el resultado es un array de uno
        if ((sizeof($result->fields)==2) && ($result->RecordCount()==1)){ 
           $temp=each($result->fields);
           $valor=$temp['value'];
           return $valor;
        }else{ 
           //Colocamos el resultado del recordset en un array
           $devolver=$result->GetArray(); 
           return $devolver;
        }
     }
  }
}

//Devuelve la clave primaria de una tabla
function pg_primaryKey($table, $separator = ',') {
         $query = "SELECT ic.relname AS index_name, a.attname AS column_name,i.indisunique AS unique_key, i.indisprimary AS primary_key, 
         case a.attnum when i.indkey[0] then 0
               when i.indkey[1] then 1
               when i.indkey[2] then 2
               when i.indkey[3] then 3
               when i.indkey[4] then 4
               when i.indkey[5] then 5
               when i.indkey[6] then 6
               when i.indkey[7] then 7
          end as orden_campos
          FROM pg_class bc, pg_class ic, pg_index i, pg_attribute a
          WHERE bc.oid = i.indrelid AND ic.oid = i.indexrelid
           AND (i.indkey[0] = a.attnum OR i.indkey[1] = a.attnum OR i.indkey[2] = a.attnum OR i.indkey[3] = a.attnum OR i.indkey[4] = a.attnum OR i.indkey[5] = a.attnum OR i.indkey[6] = a.attnum OR i.indkey[7] = a.attnum) AND i.indisunique = TRUE AND i.indisprimary = TRUE 
           AND a.attrelid = bc.oid AND bc.relname = '". $table . "' order by orden_campos";
         $result = sql($query);
         if (!$result)
            return FALSE;
         $primaryKey = "";
         $first = TRUE;
         while ($reg = each($result)){
               $data = $reg['value'];
               if ($first) {
                 $primaryKey = $table . "." . $data['column_name'];
                 $first = FALSE;
               }else{
                 $primaryKey .= $separator . $table . "." . $data['column_name'];
               }
         }
         if ($primaryKey == '')
            return FALSE;
         return $primaryKey;
}