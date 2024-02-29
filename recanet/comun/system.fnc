<?
/* Funciones de sistema tales como crear carpetas,
 * obtener la extensi�n de un archivo, etc...
 */


/*
 * Esta funci�n crea las carpetas de un path dado recursivamente
 * en el modo especificado
 */
function mkpath($path, $mode = 0700)
{ 
  $dirs = preg_split('/[\/]+/', $path);

  $path = "";
  while ( list($key, $val) = each($dirs) )
  {
     $path .= ($path != "/") ? "/" . $val : $val;
     if( !is_dir($path) && !mkdir($path, $mode) ) {
        return false;
     }
  }
  
  return true;
}
?>
