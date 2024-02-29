<?

// Devuelve una cantidad "bonita", teniendo en cuenta que est� en c�ntimos, y
// a�adiendo algo de cosm�tica (como puntos en los miles)
function impoboni ($impo) {
   $cosita = $impo;
   # Si vale 0, devolvemos "0"
   if (! $impo) return "0 &euro;";

   # Quitamos el signo
   preg_match ('/^(-)/', $impo, $signo);
   $signo = $signo[0];
   if ($signo == '-') {
     $impo = substr ($impo, 1);
   }

   # Quitamos los decimales
   $impo = round ($impo);
   /* if (preg_match ('/\./', $impo)) {
     $impo = substr ($impo, 0, strpos ($impo, "."));
   } */

   # Separaci�n de c�ntimos
   preg_match ('/.?.$/', $impo, $deci);
   $deci = $deci[0];
   if ($impo < 10)
     $deci = "0$deci";
   $impo = preg_replace ('/.?.$/', '', $impo);
   $impo = ($impo == '')?0:$impo;

   # C�lculo de comas en los miles
   while ($impo != '') {
      preg_match ('/.{0,3}$/', $impo, $foo);
      $resultado = $foo[0] . (($resultado != '')?'.':'') . $resultado;
      $impo = preg_replace ('/.{0,3}$/', '', $impo);
   }
   if ($resultado == '') $resultado = 0;
   if (preg_match ('/,.*,/', "$signo$resultado,$deci")) print "Devuelvo esto '$signo$resultado,$deci' cuando me dan esto '$cosita'<br>\n";
   return "$signo$resultado,$deci &euro;";
}


// Lo mismo que impoboni, pero preparada para imprimir en PDF
function impobonipdf ($impo) {
  # Por alguna raz�n, los ceros parece que est�n pegados al s�mbolo de euro:
  # ponemos dos espacios en vez de uno
  if ($impo == "0 &euro;")
    return "0  " . chr(128);
  return preg_replace ('/&euro;/', " " . chr(128), impoboni ($impo));
}

?>
