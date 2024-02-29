<?php

 //-------------------------------------------------------------------------------
 // Script que genera un listado en Word o Excel
 //
 // Entradas:
 // $tipo  => 0-Word, 1-Excel
 // $query => Sentencia sql
 // $titu  => Título que se muestra en el listado
 // $cabe  => Nombre de los campos de la tabla, separados por comas, cada columna 
 //           toma los valores según el orden del query. 
 //           Ej: $cabe = " Nombre, Apellidos, NIF"
 // $selec => Criterios de selección
 //-------------------------------------------------------------------------------

 include "comun/sql.fnc";

 if ($tipo=="0") {
   $file_type = "msword";
   $file_ending = "doc";
 } else {
   $file_type = "vnd.ms-excel";
   $file_ending = "xls";
 }

 header("Content-Type: application/$file_type");
 header("Content-Disposition: attachment; filename=listado.$file_ending");
 header("Pragma: cache");
 header("Expires: 0");

 $now_date = date('d-m-Y');
  
 $sep = "\t";

 // Quito las barras inclinadas con que vienen del otro lado
 $query = stripslashes ($query);
 $cabe  = stripslashes ($cabe);
 $selec = stripslashes ($selec);

 echo "&nbsp;";
 echo "<center><b>$titu</b></center><br>";

 $result = sql ($query);
 $numeregi = count ($result);   // Registros encontrados
 $numecamp = substr_count($cabe,',') + 1; // Campos solicitados
 
 if ($result) {
    echo "<center>Número de registros: $numeregi &nbsp;&nbsp;&nbsp;&nbsp; Fecha: $now_date</center><br>";
 }else{
    echo "<center><font color='red'><b>No existen registros para la búsqueda especificada</b></font></center><br><table></table>";
 }
 if (!$fontsize){
     $fontsize = 2;
 }
 if (!$fontface){
    $fontface = "Arial";
 }
 if (!$fontcolor){
    $fontcolor = "#000000";
 }
 if ($selec!=''){
    echo "<table align='center' border='1'><tr><td bgcolor='#FFD8B1' align='center' colspan=$numecamp>CRITERIOS</td> </tr><tr><td colspan=$numecamp>";
    $dato = strtok ($selec,',');
    while ($dato) {
      echo "<b>".$dato.": </b>";
      $dato = strtok (',');
      echo $dato."&nbsp;&nbsp;&nbsp;";
      $dato = strtok (',');
    }
    echo "</td></tr></table><br>";
    echo("\n");
 }

 if ($result) {
    echo "<table align='center' border='1'><tr bgcolor='#FFD8B1' align='center'>";
    $dato = strtok ($cabe,',');
    while ($dato) {
        echo "<td";
        if ($rowheight){
           echo " height=" . $rowheight;
        }
        echo "><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>";
        echo $dato."\t";
        $dato = strtok (',');
        echo "</font></td>";
    }
    echo "</tr>";
    echo("\n");
 }

 //comienza ciclo 
 if ( $result and ($numeregi == 1)) {
      $campo = each($result);
      $campo = $campo[value];
      $schema_insert = "";
      for($j=0; $j<$numecamp;$j++)
      {
         if(!isset($campo[$j])){
            $schema_insert .= "<td align='left'";
            if ($rowheight){
               $schema_insert .= " height=" . $rowheight;
            }
            $schema_insert .= "><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."NULL".$sep."</font></td>";
         }
         else
            if ( $campo[$j]  != "" && $campo[$j]  != "0001-01-01"){
               $schema_insert .= "<td ";
               if ($rowheight){
                  $schema_insert .= " height=" . $rowheight;
               }
               $schema_insert .= " align='left'><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."$campo[$j]".$sep."</font></td>";
            }
            else{
               $schema_insert .= "<td ";
               if ($rowheight){
                  $schema_insert .= " height=" . $rowheight;
               }
               $schema_insert .= " align='left'><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."".$sep."</font></td>";
            }
      }
      $schema_insert = str_replace($sep."$", "", $schema_insert);
      $schema_insert .= "\t";
      print(trim($schema_insert));
      print "\n";
      echo"</tr>";
      echo "</table>";
 } else { 
    if ($numeregi > 1) {
      if (is_array($result)){ 
         while ($campo = each ($result)){
            $campo = $campo[value];
            $schema_insert = "";
            for($j=0; $j<$numecamp;$j++)
            {
               if(!isset($campo[$j])){
                  $schema_insert .= "<td ";
                  if ($rowheight){
                     $schema_insert .= " height=" . $rowheight;
                  }
                  $schema_insert .= " align='left'><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."NULL".$sep."</font></td>";
               }
               else{
                  if ( $campo[$j]  != "" && $campo[$j]  != "0001-01-01"){
                     $schema_insert .= "<td ";
                     if ($rowheight){
                        $schema_insert .= " height=" . $rowheight;
                     }
                     $schema_insert .= " align='left'><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."$campo[$j]".$sep."</font></td>";
                  }
                  else{
                     $schema_insert .= "<td ";
                     if ($rowheight){
                        $schema_insert .= " height=" . $rowheight;
                     }
                     $schema_insert .= " align='left'><font face='" . $fontface . "' color='" . $fontcolor . "' size='" . $fontsize . "'>"."".$sep."</font></td>";
                  }
               }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
               $schema_insert .= "\t";
            print(trim($schema_insert));
              print "\n";
            echo"</tr>";
         }
      }
     echo "</table>";
    }
 }
 return (true);
?>
 
 
