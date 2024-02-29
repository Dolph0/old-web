<?
//------------------------------------------------------
// Devuelve la letra correspondiente al DNI introducido
//
// Entradas:
//   $numero => Los 8 números del DNI.
//------------------------------------------------------
function letradni ($numero) {
  $tabla = "TRWAGMYFPDXBNJZSQVHLCKET";
  $cantidad = (int)$numero / 23;
  $cantidad = (int)$cantidad * 23;
  $cantidad = $numero - (int)$cantidad;
  if ($cantidad < 0) $cantidad = -$cantidad;

  return $tabla[$cantidad];
}

//------------------------------------------------------
// Devuelve la personalidad de un sujeto a partir del
// NIF, es decir, F, J ó E.
//------------------------------------------------------
function devupers ($nifx) {
    if (ereg("^[0-9]{8}[A-Z]$",$nifx)){
       // Comprobamos si la letra es correcta
       if (substr ($nifx, 8, 1)==letradni (substr ($nifx, 0, 8))) return 'F';
    }else{
       if (ereg("^[A-H][0-9]{2}[0-9]{5}[A-Z0-9]$",$nifx)) return 'J';
       if (ereg("^[PQS][0-9]{2}[0-9]{5}[A-Z0-9]$",$nifx)) return 'E';

       if (ereg("^X[0-9]{7}[A-Z]$",$nifx)) {
         // Comprobamos si la letra es correcta
         if (substr ($nifx, 8, 1)==letradni (substr ($nifx, 1, 7))) return 'F'; // Extranjero
       }
       
       if (ereg("^N[0-9]{3}[0-9]{4}[A-Z0-9]$",$nifx)) return 'J';
       // aunque esta última puede ser también Entidad
    }
    // Si no pasó por ninguna -> error
    return '';
  }
    
?>
