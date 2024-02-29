<?

// se encarga de quitar los espacios al comienzo y 
// al final de la cadena
function quitaespa ($cad)
{
   // Quitamos los espacios al principio de la cadena
   $cad = ereg_replace ("^ +","",$cad);
   // Hacemos lo mismo con los del final
   return ereg_replace (" +$","",$cad);
}

?>
