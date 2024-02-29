<?
// Para los casos extremos en los que tenemos que salir de la ejecucion por algun error. 
// y cerrar correctamente la web. 
function fin ()
{global $codioipl;

include "comun/pie.inc";

echo "\n\n<!-- CODIGO DEL OBJETO -->\n<input type='hidden' name='codioipl' value='$codioipl'>\n\n
</form>
</body>
</HTML>";
exit;
}
?>
