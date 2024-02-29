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
  echo ("<link rel=\"stylesheet\" href=\"" . cheqroot("comun/estilo.css") . "\" type=\"text/css\">");
  // Se añade el t'itulo y la cabecera
 echo ("

    <Title>$Titulo</title>

    <center><h1>$Cabecera</h1></center>

    <br> <!-- <br><br> -->

  ");
  
  echo ("</head>\n");

  //Marca de log
  require_once("log/logger.php");
  require_once("comun/cheqsesi.fnc");
  $logger = Logger::instance();
  $logger->init();
  $sesi = cheqsesi();
  $logger->log("Usuario: " . $sesi[sesicodiusua]);

}

?>
