<?php
/*
---------------------------------------------------
Definicion del digito de control para las referencias
de cobros por ventanilla
---------------------------------------------------
Resumen:
Se definen tres numeros con la siguiente forma:
(1) Emisora
(2) Referencia
(3) Identificacion + Importe

Se hace la siguiente operacion:
(((1)*76)+((2)*9)+((3)-1)*55))/97 = {C},AA{C}
DD = 99-AA (DD=Digitos de control)

Si AA='00' el digito de control es '99'

Variables de entrada:
$Emisora			Clave de emisora junto al digito de control
$Referencia			Referencia
$Identificacion		Identificacion
$Importe			Importe

Variables de salida
$DCReferencia                   Entero con el digito de control
*/

function CrearDCReferencia($Emisora,$Referencia,$Identificacion,$Importe) {
       	$Emisora=(double)($Emisora*76);
		    $Referencia=(double)$Referencia*9;
        $Identificacion=((double)$Identificacion+(double)$Importe-1)*55;
        $Codigo = (double)($Emisora+$Referencia+$Identificacion)/97;
        $Aux = explode(".",(string)$Codigo);
        $Aux = substr($Aux[1],0,2);
        if ($Aux=="") {
          $Aux="00";
        }
        $Aux = 99 - (double)$Aux;
        $Aux=(string)$Aux;
        if (strlen($Aux)==1) {
          $Aux="0".$Aux;
        }
        return (string)$Aux;
}

/*
---------------------------------------------------
Verificacion  del digito de control para las referencias
de cobros por ventanilla
---------------------------------------------------
Resumen:
Se calcula la clave de control y se verifica que sea la misma que la interrogada 

Variables de entrada:
$Emisora                        Clave de emisora junto al digito de control
$Referencia                     Referencia
$Identificacion                 Identificacion
$Importe                        Importe
$CC								Clave de control a verificar

Variables de salida
$VerificarDCReferenciaCobros   TRUE si el numero de control es correcto , FALSE si no lo es 

*/

function VerificarDCReferenciaCobros($Emisora,$Referencia,$Identificacion,$Importe,$CC) {
  // echo "<br> en veridficar $Emisora,$Referencia,$Identificacion,$Importe,$CC ";
  $NCC = CrearDCReferencia($Emisora,$Referencia,$Identificacion,$Importe);
  //echo "<br> nc Es ".$NCC;
  $chek=false;
  if ($NCC == $CC) {
    $chek=true;
  }
  return $chek;
}

function Generar_codigo($longitud) {
  mt_srand((double)microtime()*1000000);
  $codigo=md5(uniqid(mt_rand()));
  $codigo=substr($codigo, 0, $longitud);
  return $codigo;
}

/* Funcion que genera el codigo de sesion cuando se establece una conexion */
function Codigo_sesion() {
  // 1.- random de un número aleatorio
  // 2.- comprobar que no existe en el fichero
  // 3.- devolver sesion
  srand(time());
  $fin = "N";
  while ( $fin == "N" || $i<5) {
	$i++;
    $nume=Generar_codigo(26);
    $query= "SELECT sesion_id FROM regioper WHERE sesion_id='$nume'" ;
    $aux = Sql ( $query );
    if ( $aux == "" ) { $fin = "S"; }
  }
  return $nume;
}