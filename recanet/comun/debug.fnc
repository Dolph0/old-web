<?
// Se incluye donde nos interesa.. 
//Muestrar solo en pantalla las variables que ha generado un documento HTML en un formulario. 
//
//  VARIABLES POST / GET
//------------------------
// VARIABLE |    VALOR

function infovari ()
{
  global $HTTP_POST_VARS,$HTTP_GET_VARS;
 echo "<div class='solopantalla'><center>
 <table width=100%>
  <tr><td>";
 if ($HTTP_POST_VARS)
 {
  echo "<table width=49% align=left>\n <tr><td colspan=2 class=tituform>VARIABLES POST</td></tr>";
  foreach ($HTTP_POST_VARS as $titulo => $valor)
   {
    echo "\n <tr><td class=cabeform width=30%>$titulo</td><td class=derform> $valor </td></tr>";
    if (is_array ($valor)) foreach ($valor as $t => $v) echo "<tr><td class=cabeform align=right>[$t]</td><td class=derform>$v</td></tr>";
   }
  echo "</table>";
 }
 if ($HTTP_GET_VARS)
 {
  echo "<table width=49% align=left>\n <tr><td colspan=2 class=tituform>VARIABLES GET</td></tr>";
  foreach ($HTTP_GET_VARS as $titulo => $valor)
   {
     echo "\n <tr><td class=cabeform width=30%>$titulo</td><td class=derform> $valor </td></tr>";
     if (is_array ($valor)) foreach ($valor as $t => $v) echo "<tr><td class=cabeform align=right>[$t]</td><td class=derform>$v</td></tr>";
   }
  echo "</table>";
 }
 echo "</td></tr></table></div></center>";
}

// Permite calcular el tiempo de ejecucion de una rutina o de 
// un fragmento de codigo simplemente tendremos que pasarle 
// El instante en el que comenzo a realizar el calculo. 
// tiene un error de  0.01 a 0.005 aprox. microsegundos 
// tiempo de ejecucion de esta funcion. 
function tiempoejec ($inicio)
{
   // se recoge el instante en el que ha terminado
  $final = microtime ();

  $i = explode (' ', $inicio);
  $inicio = (float) $i[1] + (float) $inicio;

  $f = explode (' ', $final);
  $final =  (float) $f[1] + (float) $final;

 echo "<br>Tiempo de utilizado:<br>";
 echo (float) $final - $inicio;
 echo "<br>";
 echo (float)$inicio -  $final;
 echo "<br>";
}

  
  
  
  


?>
