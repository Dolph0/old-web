<?
//Funciones para localizar errores gracias al fabuloso sistema de php

//Muestra en una tabla las variables que ha generado un documento HTML en un formulario. 
// NOMRE DE LA VARIABLE: VALOR DE LA VARIABLE. 
//MODIFICACIONES
// · Se diferencia entre variables POST y GET muestra aquellas que lleguen 

function variablespost ()
{
  global $HTTP_POST_VARS,$HTTP_GET_VARS;
 if ($HTTP_POST_VARS)
 {
  echo "<table><tr><td colspan=2 class=tituform>VARIABLES POST</td></tr>";
  foreach ($HTTP_POST_VARS as $titulo => $valor)
   echo "<tr><td class=cabeform>$titulo</td><td class=derform> $valor </td></tr>";
  echo "</table>";
 }
 if ($HTTP_GET_VARS)
 {
  echo "<table><tr><td colspan=2 class=tituform>VARIABLES GET</td></tr>";
  foreach ($HTTP_GET_VARS as $titulo => $valor)
   echo "<tr><td class=cabeform>$titulo</td><td class=derform> $valor </td></tr>";
  echo "</table>";
 }
}


?>
