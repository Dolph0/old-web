
<?php
include "../database/func.inc";

// Insertamos el registro de la petición en la BD
$query="INSERT INTO regioper (idenno60, refeno60, impo, tipopago, fechhora) values ('$_POST[identificacion]', '$_POST[referencia]','$_POST[importe]','LA CAIXA', current_timestamp)";
Sql($query);

include "../euro.fnc";

$importe = str_pad(euro2cent ($_POST[importe]), 8, "0", STR_PAD_LEFT); 

print("
	<html>
	<head><title>Pago de tributos municipales</title></head>
	<body onload=\"form.submit();\">
	<form name=form method=post action=\"https://lop.caixabank.es/GPeticiones?FLAG_BORSA=0&PE=1&DEMO=0&PN=LGN&IDIOMA2=02&CANAL=I&IDIOMA=02&INICIAL_PN=GPA&INICIAL_PE=30&ORIGEN=CAI&ENTORNO=L&E_CODBAR=90521350126".$_POST[referencia].$_POST[identificacion].$importe."0\">
	</form>
	</body>
	</html>
");
?>
