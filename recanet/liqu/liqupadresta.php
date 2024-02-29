<?
include "comun/func.inc";
include "comun/ajax.js.php";

$sesi = cheqsesi(); // chequea la sesión
$codiusua = $sesi[sesicodiusua];

# Comprueba si el usuario tiene permiso para entrar en la pagina
if ( !cheqperm( "gestliqu", $sesi[sesicodiusua] ) && !cheqperm( "ECOS", $sesi[sesicodiusua] )){
  segu( "Intenta entrar a estado de padrones sin permiso" );
}
  
if (!isset($codiayun)){
   $codiambi = sql ("SELECT codiambi FROM usua where codiusua = '$sesi[sesicodiusua]'");
}else{
   $codiambi = $codiayun;
}

print "<SCRIPT language='JavaScript' src='" . cheqroot("comun/fecha.js") . "'></SCRIPT>\n";
print "<SCRIPT language='JavaScript' src='" . cheqroot("liqu/liqu.js") . "'></SCRIPT>\n";
print "<SCRIPT language='JavaScript' src='" . cheqroot("comun/vent.js") . "'></SCRIPT\n>";

cabecera("Estado de padrones");

print( "<body>\n" );

// página recursiva
print("<form name = 'form' method = 'post' action = '$estaurlx.php'>\n");

?>

<center>
<div id='padrcalcinic'>
</div>
</center>
<script>
      	refrcalcinic(<? print $codiambi ?>, <? print $codiusua ?>);
</script>
</form>
<?

 include "comun/pie.inc";
?>

</body>
</html>
                                                                                         
