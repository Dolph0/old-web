<?php
# Funcion para convertir de euros a c�ntimos, *con redondeo inclu�do*
// Recibe euros por parametro, y devuelve en centimos, tras redondear
function euro2cent ($inicial)
{
 $inicial = str_replace(',', '.', $inicial );
 $mitad = (double) strstr ($inicial*100, '.');
 $inte  = (double) doubleval ($inicial*100);
 $valor = (double) substr ($mitad, 1,1);
 if ($valor > 4){ $inte+=1;}
 
 return (round ($inte));
}


// Devuelve una cantidad "bonita", teniendo en cuenta que est� en c�ntimos, y
// a�adiendo algo de cosm�tica (como puntos en los miles)
function impoboni ($impo, $b_mostrarceros = true) {
   # Si vale 0, devolvemos "0"
   if (!$impo)
     if ($b_mostrarceros) return "0 &euro;";
     else return '';

   # Quitamos el signo
   preg_match ('/^(-)/', $impo, $signo);
   $signo = $signo[0];
   if ($signo == '-') {
     $impo = substr ($impo, 1);
   }

   # C�lculo de comas en los miles y decimales
   $impo = number_format ($impo*0.01, 2, ',', '.');
   
   return "$impo &euro;";
}


// Lo mismo que impoboni, pero preparada para imprimir en PDF
function impobonipdf ($impo) {
  # Por alguna raz�n, los ceros parece que est�n pegados al s�mbolo de euro:
  # ponemos dos espacios en vez de uno
  if ($impo == "0 &euro;")
    return "0  " . chr(128);
  return preg_replace ('/&euro;/', " " . chr(128), impoboni ($impo));
}