<?
include "comun/func.inc";

$sesi = cheqsesi();

cabecera("Actualizar Nombre del Sujeto");

sql("UPDATE tercdato SET nomb='$nombsuje' WHERE nifx='$nif'");

?>
Se ha modificado el nombre del sujeto con NIF <b><? echo $nif; ?></b> y nombre<b> <? echo $nomb; ?> </b> a <b> <? echo $nombsuje; ?> </b><br><br>

<center><b><font color="red">¡Debe volver a insertar o actualizar el objeto!</font></b></center>

<form>
<center>
<input type="button" value="Aceptar" onclick="window.close();">
</center>
</form>

