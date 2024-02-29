<?php
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "idioma/idioma_".$idioma.".php";

print("
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<link rel=\"stylesheet\" href=\"css/estilos.css\" type=\"text/css\">

<script language=\"JavaScript\">

function Comenzar() {
	var ancho=screen.availWidth;
	var capa=document.getElementById('capa_folleto');
	capa.style.visibility='visible';
	capa.style.left=ancho-205-200;
	capa.style.top=-430;
	ArrancarTimer();
}

var timerID=0;
var Aparicion=false;
var Movimiento=false;
var Reposo=false;

function ArrancarTimer() {
	
   	if(timerID) clearTimeout(timerID);
	
	// Esperar 3 segundos antes de mostrar el cartel
   	if (!Aparicion) { Aparicion=true; timerID=setTimeout(\"TratarTimer()\",3000); }
	
	// Esperar 3 segundos antes de ocultar el cartel
	else if (Reposo&&!Movimiento) { timerID=setTimeout(\"TratarTimer()\",10000); }
	
	// Esperar 10 milisegundos entre movimientos del cartel
	else if (Movimiento) timerID=setTimeout(\"TratarTimer()\",10);
}

function PararTimer() {
   if(timerID) { clearTimeout(timerID); timerID=0; }
}

function TratarTimer() {

	PararTimer(timerID);

	// Ya ha pasado el tiempo de espera antes de la bajada.
	if (Aparicion)
	
	 	// Hay que comenzar a bajar o subir el cartel.
		if (!Movimiento) { Movimiento=true; ArrancarTimer(); }
		
		else {
		
			var capa=document.getElementById('capa_folleto');
			var inc;
			if (Reposo) inc=-10; else inc=10;
			capa.style.top=parseInt(capa.style.top)+inc;
			if ((Reposo&&(parseInt(capa.style.top)>-430))||
			   (!Reposo&&(parseInt(capa.style.top)<10)))
				ArrancarTimer();
			
			// Hay que comenzar a subir el cartel
			else if (!Reposo) { 
				Reposo=true; Movimiento=false; ArrancarTimer(); 
			}
			
			// Se acabó todo el proceso. Esperar 10 segundos antes de repetir todo el proceso.
			else {
				Aparicion=false; Reposo=false; Movimiento=false;
				timerID=setTimeout(\"ArrancarTimer()\",10000);
			}
		}

}

</script>

</head>
");
# Print con banner subiendo y bajando
#print ("<body onload=\"Comenzar();\" onunload=\"PararTimer();\" bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">");
# Print con banner fijo
print ("<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">");

print ("
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
  <tr>
    <td valign=\"middle\" align=\"center\"><img src=\"imagenes/".$idioma."/tit_bienvenida.gif\" width=\"574\" height=\"51\"><br>");

print ("<video width=\"574\" height=\"228\" autoplay loop muted>
		<source src=\"videos/".$idioma."/home.mp4\" type=\"video/mp4\">
		</video>");

print ("</td>
    <td>
   <!--   <a href=\"download/Triptico_Impuestos.pdf\" target=\"_blank\">
        <img src=\"imagenes/divide_por_dos.png\" width=\"205\" height=\"430\" border=\"0\" alt=\"Haga clic aqu&#237; para m&#225;s informaci&#243;n\">      
      </a> -->
      <a href=\"menu/pago_impuestos/pago_impuestos.php?idioma=".$idioma."\" target=\"contenido\">
        <img src=\"imagenes/pago_tpv.png\" width=\"200\" height=\"110\" border=\"0\" alt=\"Haga clic aqu&#237; para m&#225;s informaci&#243;n\">      
      </a> 
    </td>
  </tr>
  <tr>
    <td valign=\"bottom\" height=\"33\" align=\"center\" class=\"td_firma\" colspan=\"2\">Mog&#225;n Gesti&#243;n Municipal, 
	 Telf 928 15 88 06<br>
      ".$IDI_DESA." RecaNet</td>
  </tr>
</table>
</center>

<!-- Esta es la antigua capa que había 
<div id=\"capa_folleto\" style=\"width: 408px; height: 430px; visibility: hidden; position: absolute; top: 5px; left: 0px;\">
<img src=\"imagenes/diptico_portada.jpg\" width=\"408\" height=\"430\" border=\"0\" alt=\"\">
</div>
-->

<!-- La otra antigua capa 2009-03-06
<div id=\"capa_folleto\" style=\"width: 205px; height: 430px; visibility: hidden; position: absolute; top: 5px; left: 0px;\">
<img src=\"imagenes/nueva_portada.gif\" width=\"205\" height=\"430\" border=\"0\" alt=\"\" usemap=\"#mapa_portada\">
<map name=\"mapa_portada\">
<area alt=\"\" coords=\"14,330,131,411\" href=\"download/folleto_informativo.zip\">
</map>
</div>
-->

</body>
</html>
");