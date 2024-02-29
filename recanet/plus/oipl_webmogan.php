<?php

define ('hoy', date('d-m-Y',time())); 

// INCLUIMOS FICHEROS NECESARIOS. 
include "../comun/func_webmogan.inc";
include "../comun/fecha.fnc";
include "include/inmu_webmogan.fnc";
include "include/domi_webmogan.fnc";
include "include/cuot.fnc";
include "include/comp.fnc";
include "include/fin.fnc";
include "../comun/inseterc.new.fnc";
include "../liqu/cuot.php";

include "../comun/loadGETPOST.inc";

function mostrartodo() {
  global $CUOTA_PLUSVALIA_CALCULADA;
  echo "<center>";
  inmueble('M',$CUOTA_PLUSVALIA_CALCULADA);       //IDENTIFICACION
  // domicilio ('tran');//DATOS DEL TRANSMITENTE
  // domicilio ('adqu');//DATOS DEL ADQUIRENTE
  // domicilio ('repr');//DATOS DEL DECLARANTE
  // calculocuota ();   //DATOS PARA EL CÁLCULO DE LA CUOTA
  // complementario();  // DATOS COMPLEMENTARIOS 
}

$CUOTA_PLUSVALIA_CALCULADA;

$codiambi = 4;
/*
$sesi = cheqsesi(); // chequea la sesión

$codiambi = sql ("SELECT codiambi FROM usua WHERE codiusua='$sesi[sesicodiusua]'");

# Comprueba si el usuario tiene permiso para entrar en la página
if ( !cheqperm( "TIPL", $sesi[sesicodiusua] ) )
  segu( "Intenta entrar a las páginas de gestión de plusvalías sin permiso" );
*/

$estaurlx = estaurlx();

echo "<HTML>\n";
cabecera("");
echo "\n<BODY>\n <FONT FACE='Arial'>\n";

// asigna el archivo de funciones del lado del cliente
// mientras se depura el enlace a la página por nombre
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("plus/" . $estaurlx . ".js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/hint.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/misc.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/muni.js") . "'></SCRIPT>";
echo "\n  <SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/fecha.js") . "'></SCRIPT>\n";
echo "\n  <SCRIPT LANGUAGE='JavaScript'>var comprobaciones,limpieza;</SCRIPT>\n";

print("
	<script language='JavaScript'>
		function LimpiarControles() {
			form.codiviax.value = \"\"; form.nume.value = \"\";	form.letr.value = \"\";	form.esca.value = \"\";
			form.plan.value = \"\";	form.puer.value = \"\";	form.refecata.value = \"\";	form.numecarg.value = \"\";
			form.caracont.value = \"\";	form.nfij.value = \"\";	form.idenloca.value = \"\";	form._fechdeve.value = \"\";
			form._fechtran.value = \"\"; form._cuotadqu.value = \"\"; form.nombinmu.selectedIndex=0;
			form.codiviax.selectedIndex=0; 
                        //form.codisigl.selectedIndex=0;
			//document.getElementById(\"BotonMapa\").style.visibility=\"hidden\";
		}
		
		function VerMapa() {
			var refe=document.getElementById('refecata').value;
			if (refe.length!=14) alert('Error: referencia catastral errónea.');
			else {
var windowprops=\"location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes,width=380,height=320\";
window.open('/callejero/mapa_plusvalia/index.php?refeplus='+refe,'hija',windowprops);
			}
		}
		
	</script>
");

// Calculo de la plusvalía
if ($_POST['calcular_plusvalia']=="si") {
	

	// Años transcurridos
	$_aniotran=floor(abs(numeMeses(guardarfecha($_fechtran),guardarfecha($_fechdeve)))/12);
	 // Porcentaje del período
	 //Segun los años transcurridos calculamos el porcentaje del periodo,
	 $porcperi = porcperiplus ($codiambi, guardarfecha($_fechdeve), $_aniotran);

	// Valor catastral (calculado directamente desde los datos de la BBDD de RecaNet)
	if (!$valocatausua) {
	    $plantita = substr($plan,0,2); if ($plantita =='B0') $plantita ='00'; if (!$plantita) $plantita='  ';
	    $escalerita = substr($esca,0,1); if (!$escalerita) $escalerita=' ';
	    $puertita = substr($puer,0,2); if (!$puertita) $puertita='  ';
	    $comprobar=$escalerita.$plantita.$puertita;
	    if ($comprobar=='SUELO') $comprobar='SOLAR';
	    $codiobje=$refecata.$comprobar;
	    $codiobje2=$refecata.$numecarg.$caracont;
	    $aniomeno=''; $anio=substr(guardarfecha($_fechdeve),0,4);
            $sql_valocata="SELECT valocata,refecata FROM webmogan_oibivalocata WHERE anio='$anio' ";
	    if ($anio<=2001) $sql_valocata .= " AND refecata = '$codiobje' ";
	    else $sql_valocata.=" AND refecata = '$codiobje2' ";
	    $regi=sql($sql_valocata);
	    if (!$regi) {
	      $aniomeno=$anio-1;
	      $sql_valocata="SELECT valocata,refecata FROM webmogan_oibivalocata WHERE anio='$aniomeno' ";
	      if ($aniomeno<=2001) $sql_valocata.=" AND refecata = '$codiobje' ";
	      else $sql_valocata.=" AND refecata = '$codiobje2' ";
	      $regi=sql($sql_valocata);
	    }
	    if (is_array($regi)) { $regi=each($regi); $valor=$regi['value']; }
	} else $regi=true;
	// No se pudo obtener el valor catastral. Hay que pedirlo.
	if (!$regi) {
		echo "<script language=\"JavaScript1.2\">alert('No se pudo obtener el valor catastral del suelo. Por favor, introduzca este valor en el formulario.');</script>";
		$CUOTA_PLUSVALIA_CALCULADA="INTRODUCIR";
	} else {
		// Calcular el valor catastral aplicable
		if (!$valocatausua) {
			if (is_array($regi)) {
				$_valocata=$valor['valocata'];
			}
			if ($aniomeno) {
		       		$reviurba = sql ("select reviurba from ayun where codiayun=$codiambi");
		       		if (!($reviurba!="" && $reviurba>1997 && $reviurba<=$anio && $anio<=$reviurba+9)) {
					$incremento=sql ("select incrurba from valoanua where anio='$anio' order by anio");
					$_valocata=euro2cent((($_valocata*($incremento/100))+$_valocata)/100);
		       		}
			}
		}
		
		if ($valocatausua) $_valocata=euro2cent($valocatausua);
		if ($_metovalo=='NO') $_valocata=round($_supesola*$_valocatam2*$_cuotpart);
		$anio=substr($_fechdeve,-4); $dedu=sql("SELECT deduvalosuel FROM valoanua WHERE anio='$anio'");
		$dedu=$dedu/100; $_valoapli=euro2cent(($_valocata/100)*(1-$dedu));
	
		// Cálculo de los vectores "busc" y "oper" para llamar a "calccuot"
		if ($_aniotran>20) $_aniotran=20;
		$busc[10]=$_aniotran;
		$oper[10]=($_valoapli*0.01)*$_aniotran;
		$oper[10]*=$porcperi*$_cuotadqu*0.01;
	
		// Llamar a "calccuot" para calcular el valor de la plusvalía
		$plus=calccuot(25,substr(guardarfecha($_fechdeve),0,4),4,$busc,$oper,"");
		$CUOTA_PLUSVALIA_CALCULADA=$plus['cuot'];
	}
}

include "../comun/gui.fnc";

// página recursiva
echo "<form name='form' method='post' action='$estaurlx.php'>\n";

if ($opci == 'Limpiar'|| $opci =='Eliminar' || !$opci)  {include "include/oiplinic_webmogan.inc";} 

// include "comun/pie.inc";

echo "\n\n<!-- CODIGO DEL OBJETO -->\n<input type='hidden' name='codioipl' value='$codioipl'>\n\n
</form>
</body>
</HTML>";
