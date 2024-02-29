
<?php
include "../database/func.inc";

// Insertamos el registro de la petición en la BD
$query="INSERT INTO regioper (idenno60, refeno60, impo, tipopago, fechhora) values ('$_POST[identificacion]', '$_POST[referencia]','$_POST[importe]','LA CAJA DE CANARIAS', current_timestamp)";
Sql($query);

print("

	<html>
	<head><title>Pago de tributos municipales</title></head>
	<body onload=\"form.submit();\">
	<form name=form method=post action=\"http://pccaja.atca.es/banca4/tx7027/previo.jsp\">
	<input type=hidden name=emisora maxlength=\"6\" value=\"350126\">
	<input type=hidden name=referencia maxlength=\"12\" value=\"".$_POST[referencia]."\">
	<input type=hidden name=identificacion maxlength=\"10\" value=\"".$_POST[identificacion]."\">
	<input type=hidden name=importe maxlength=\"10\" value=\"".$_POST[importe]."\">
	</form>
	</body>
	</html>

");

?>
