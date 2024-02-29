<?php

function convert($content) {

	GLOBAL $registro,$dato;

	$a=0;
	foreach($_POST as $symbol=>$value) {

		// Sustituir los campos en blanco con los valores que ha rellenado el usuario
		$content=str_replace("[<".$symbol.">]",$value,$content);

		// Guardar los datos del formulario rellenado
		$registro[count($registro)]=$dato[$a++].": ".$value;
	}

	return $content;
}

// Inicializar el registro de almacenamiento de formularios
$registro=array();

// Inicializar los datos pedidos en el formulario
$dato[0]="Nombre";
$dato[1]="Acredito con";
$dato[2]="Nº";
$dato[3]="Actua en nombre propio";
$dato[4]="Acredito con";
$dato[5]="Nº";
$dato[6]="Domicilio";
$dato[7]="Localidad";
$dato[8]="Teléfono";
$dato[9]="Fax";
$dato[10]="Expone";
$dato[11]="Solicita";
$dato[12]="Documentación";
$dato[13]="Dia";
$dato[14]="Mes";
$dato[15]="Año (201)";

header("Content-Type: application/msword");
header("Content-Disposition: inline;");

// Mostrar el documento en formato word para la impresión
$file="../../formularios/generico.rtf";
$fp=fopen($file,"r");
$content=fread($fp,filesize($file));
$content=convert($content);
print $content;

// Almacenar los datos en un fichero de la web
$fr=@fopen("../../registro/generico.txt","a+");
for ($a=0;$a<count($registro);$a++)
	@fwrite($fr,"
".$registro[$a]);
@fwrite($fr,"
------------------------------------------------------------------------------");

?>
