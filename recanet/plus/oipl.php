<?
define ('hoy', date('d-m-Y',time())); 

// INCLUIMOS FICHEROS NECESARIOS. 
include "comun/func.inc";
include "comun/fecha.fnc";
include "plus/include/inmu.fnc";
include "plus/include/domi.fnc";
include "plus/include/cuot.fnc";
include "plus/include/comp.fnc";
include "plus/include/fin.fnc";
include "comun/inseterc.new.fnc"; 

function mostrartodo() {
  echo "
  <center>
  <table cellpadding=2 cellspacing=1 border=0 width=100%>
    <tr><td colspan=2 ><b>Campos obligatorios en negrita</b></td></tr>
  </table>
  ";
  inmueble ();       //IDENTIFICACION
  domicilio ('tran');//DATOS DEL TRANSMITENTE
  domicilio ('adqu');//DATOS DEL ADQUIRENTE
  domicilio ('repr');//DATOS DEL DECLARANTE
  calculocuota ();   //DATOS PARA EL CÁLCULO DE LA CUOTA
  complementario();  // DATOS COMPLEMENTARIOS 
}


$sesi = cheqsesi(); // chequea la sesión

$codiambi = sql ("SELECT codiambi FROM usua WHERE codiusua='$sesi[sesicodiusua]'");

# Comprueba si el usuario tiene permiso para entrar en la página
if ( !cheqperm( "TIPL", $sesi[sesicodiusua] ) )
  segu( "Intenta entrar a las páginas de gestión de plusvalías sin permiso" );

$estaurlx = estaurlx();

echo "<HTML>\n";
cabecera("Impuesto sobre el Incremento del Valor de los Terrenos Urbanos (Plusvalía)<br> Entrada de datos ");
echo "\n<BODY>\n <FONT FACE='Arial'>\n";

// asigna el archivo de funciones del lado del cliente
// mientras se depura el enlace a la página por nombre
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("plus/" . $estaurlx . ".js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/hint.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/misc.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/muni.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/fecha.js") . "'></SCRIPT>\n";
include "comun/gui.fnc";

// página recursiva
echo "<form name='form' method='post' action='$estaurlx.php'>\n";

##### muestra pantalla dependiendo de la procedencia de la llamada
if ($opci == "Buscar")     {include "plus/include/oiplbusc.inc";} 
if ($opci == "Crear"  || $opci == "Modificar")  {
   //Comprobamos si existe otro sujeto con el mismo nif          
  $resultran = false;
  $resuladqu = false;
  $resulrepr = false;

  if ($trannomb!="" && $trannifx!="") $resultran = compsuje ($trannifx, $trannomb);
  if ($resultran == false) {
    if ($adqunomb!="" && $adqunifx!="") $resuladqu = compsuje ($adqunifx, $adqunomb);
    if ($resuladqu == false) {
      if ($reprnomb!="" && $reprnifx!="") $resulrepr = compsuje ($reprnifx, $reprnomb);
    }
  }

  if ($resultran == true || $resuladqu == true || $resulrepr == true) { 
    opci ("Liquidar:Modificar:Crear:Eliminar:Limpiar");
    mostrartodo ();
  } else { 
    include "plus/include/oiplcrea.inc"; 
  }
}

if ($opci == 'Limpiar'|| $opci =='Eliminar' || !$opci)  {include "plus/include/oiplinic.inc";} 

include "comun/pie.inc";
echo "\n\n<!-- CODIGO DEL OBJETO -->\n<input type='hidden' name='codioipl' value='$codioipl'>\n\n
</form>
</body>
</HTML>";
