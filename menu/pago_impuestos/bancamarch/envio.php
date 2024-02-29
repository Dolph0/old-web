<?php
include "../database/func.inc";

// Insertamos el registro de la petición en la BD
$query="INSERT INTO regioper (idenno60, refeno60, impo, tipopago, fechhora) values ('{$_POST['identificacion']}', '{$_POST['referencia']}','{$_POST['importe']}','BANCA MARCH', current_timestamp)";
Sql($query);

print("

	<html>
	<head><title>Pago de tributos municipales</title></head>
	<body onload=\"form.submit();\">
	<form name=form method=post action=\"https://telemarch.bancamarch.es/Pagos/Manager\">
	<input type=hidden name=c01784 value=\"000350126\">
	<input type=hidden name=modalidad  value=\"2\">
	<input type=hidden name=c01459 value=\"EUR\">
	<input type=hidden name=idioma value=\"0\">
	<input type=hidden name=PA value=\"2\">
	<input type=hidden name=c90100 value=\"00001\">
	<input type=hidden name=c00495 value=\"Ayuntamiento de Mogán\">
	<input type=hidden name=c00178 value=\"".$_POST['referencia']."\">
	<input type=hidden name=identificador value=\"".$_POST['identificacion']."\">
	<input type=hidden name=c01327 value=\"".$_POST['importe']."\">
	</form>
	</body>
	</html>

");