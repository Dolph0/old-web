<?
// Esta función, guarda una copia de seguridad cuando un cargo es ingresado, anulado
// o que se haya vuelto a poner pendiente de cobro.
// Es util para no perder los datos de lo cobrado/anulado durante la mañana, si el
// servidor cae y perdemos lo almacenado en la base de datos, ya que solo
// dispondremos en ese caso de la copia de seguridad de la base de datos del dia anterior.
// Se usa en Ingresos y en Otras bajas contables, de la Gestión Recaudatoria,
// concretamente en los scripts rcoi/gesting.php, rcoi/gestcaja.php y rcon/gestnoingr.php
// La copia de seguridad de cada baja contable se guarda en un fichero, el cual se 
// envia por FTP a otra maquina, la cual debe contar con servidor FTP habilitado.
// El parametro que recibe es un vector con los datos del cargo

function copiestacont( $vectcarg ) {
  // Recibe un vector de cargos que se modifican al mismo estado contable.

  // En el nombre del fichero voy a indicar la fecha y hora de creacion,
  // ademas de la IP del equipo donde se efectua la baja contable, para evitar coincidencias
  $dire = "/var/datos/segu/";
  $nombfich = date( 'YmdHis', time() ).".".getenv("REMOTE_ADDR").".txt";

  $fich = fopen( $dire.$nombfich, "w" );
  // Cabecera de los datos
  $dato = "codiconc;ejer;numedocu;nifx;estacont;fechestacont;deud;codiobje\n";
  $dato .= "fechcont;horacont;usuacont;pericont;proccont;porcreca;imporeca;impointe\n";
  $dato .= "-----------------------------------------------------------------------------------------------\n";
  // Imprimo los datos
  while( $carg = each( $vectcarg ) ) {
    $carg = $carg[value];
    $dato .= $carg[codiconc].";".$carg[ejer].";".$carg[numedocu].";".$carg[nifx].";".$carg[estaviej]."->";
    $dato .= $carg[estacont].";".$carg[fechestacont].";".$carg[deud].";".$carg[codiobje]."\n";
    $dato .= $carg[fechcont].";".$carg[horacont].";".$carg[usuacont].";".$carg[pericont].";";
    $dato .= $carg[proccont].";".$carg[porcreca].";".$carg[imporeca].";".$carg[impointe]."\n";
    $dato .= "-----------------------------------------------------------------------------------------------\n";
  }

  fwrite( $fich, $dato );
  fclose( $fich );
}
?>
