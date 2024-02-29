<?
//----------------------------------------------------------------
// Devuelve una cantidad "bonita" representando la superficie en 
// hect�reas y a�ade algo de cosm�tica (como puntos en los miles)
//
// Entradas:
//   $supe => Valor entero
//
// Salida:
//   La cantidad expresada en bonito.
//----------------------------------------------------------------
function supeboni ($supe) {
   # Si vale 0, devolvemos "0"
   if (! $supe) return "0 Ha";
   
   # Quitamos los decimales
   $supe = round ($supe);
   if ($supe < 10) 
     $supe = "000$supe";
     else
     if ($supe < 100) 
       $supe = "00$supe";
      else
        if ($supe < 1000) 
          $supe = "0$supe";
       
   # Separaci�n de c�ntimos
   preg_match ('/.?...$/', $supe, $deci);
   $deci = $deci[0];
   
   $supe = preg_replace ('/.?...$/', '', $supe);
   $supe = ($supe == '')?0:$supe;
   
   # C�lculo de comas en los miles
   while ($supe != '') {
      preg_match ('/.{0,3}$/', $supe, $foo);
      $resultado = $foo[0] . (($resultado != '')?'.':'') . $resultado;
      $supe = preg_replace ('/.{0,3}$/', '', $supe);
   }
   
   # Quitamos los ceros del final
   $deci=preg_replace('/0*$/','',$deci);

   if ($resultado == '') $resultado = 0;
   if ($deci=='') return "$resultado Ha";
   return "$resultado,$deci Ha";
}


// Lo mismo que supeboni, pero preparada para imprimir en PDF
function supebonipdf ($supe) {
  # Por alguna raz�n, los ceros parece que est�n pegados al s�mbolo de Ha:
  # ponemos dos espacios en vez de uno
  if ($supe == "0 Ha")
    return "0  " . chr(128);
  return preg_replace ('/Ha/', " " . chr(128), supeboni ($supe));
}
?>