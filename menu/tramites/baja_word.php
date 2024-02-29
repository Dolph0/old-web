<?php

function convert($content) {

	GLOBAL $registro,$dato;

	$a=0;
	foreach($_POST as $symbol=>$value) {

		// Sustituir los campos en blanco con los valores que ha rellenado el usuario
		$content=str_replace("[<".strtoupper($symbol).">]",$value,$content);

		// Guardar los datos del formulario rellenado
		$registro[count($registro)]=$dato[$a++].": ".$value;
	}

	return $content;
}

// Inicializar el registro de almacenamiento de formularios
$registro=array();

// Inicializar los datos pedidos en el formulario
$dato[0]="Nombre";
$dato[1]="NIF";
$dato[2]="Domicilio fiscal";
$dato[3]="N�";
$dato[4]="Poblaci�n";
$dato[5]="E/PL/PT";
$dato[6]="C. Postal";
$dato[7]="Tel�fono";
$dato[8]="C�digo Banco";
$dato[9]="C�digo Oficina";
$dato[10]="D�gito Control";
$dato[11]="N�mero Cuenta";
$dato[12]="Nombre Banco";
$dato[13]="Calle";
$dato[14]="N�";
$dato[15]="C�digo postal";
$dato[16]="Titular recibo";
$dato[17]="NIF";
$dato[18]="Concepto (1)";
$dato[19]="Objeto Tributario (1)";
$dato[20]="Concepto (2)";
$dato[21]="Objeto Tributario (2)";
$dato[22]="Concepto (3)";
$dato[23]="Objeto Tributario (3)";
$dato[24]="Concepto (4)";
$dato[25]="Objeto Tributario (4)";
$dato[26]="D�a";
$dato[27]="Mes";
$dato[28]="A�o (200)";


header("Content-Type: application/msword");
header("Content-Disposition: inline;");

// Mostrar el documento en formato word para la impresi�n
$file="../../formularios/baja.rtf";
$fp=fopen($file,"r");
$content=fread($fp,filesize($file));
$content=convert($content);
print $content;

// Almacenar los datos en un fichero de la web
$fr=@fopen("../../registro/baja.txt","a+");
for ($a=0;$a<count($registro);$a++)
	@fwrite($fr,"
".$registro[$a]);
@fwrite($fr,"
------------------------------------------------------------------------------");

?>
