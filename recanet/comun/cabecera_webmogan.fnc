<?
function cabecera( $Titulo ) {
         imprcabecera($Titulo, $Titulo);
}

function cabeceraAviso($Cabecera) {
         imprcabecera("Aviso", $Cabecera);
}

function imprcabecera($Titulo, $Cabecera){
# Devuelve el identificador del proceso actual (por si tenemos que "matarlo"
  $proceso = "<h1>PROCESO:".posix_getpid ()."</h1>";
  //echo ("$proceso<head>\n");
  echo ("<head>\n");
  // Se añade la hoja de estilo que va a usar la aplicacion
  echo ("<link rel=\"stylesheet\" href=\"" . cheqroot("comun/estilo_webmogan.css") . "\" type=\"text/css\">");
  // Se añade el t'itulo y la cabecera
 echo ("<Title>$Titulo</title><center><h1>$Cabecera</h1></center></head>\n");
}

?>
