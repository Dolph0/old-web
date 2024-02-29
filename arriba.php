<?php
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "idioma/idioma_".$idioma.".php";

print("
	<html>
	<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"css/estilos.css\" type=\"text/css\">
	
	<script language=\"JavaScript\">
		var mostrar_advertencia=true;
	</script>
	
	</head>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
	    <td height=80% colspan=\"2\" valign=\"bottom\"> 
	      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	        <tr> 
	          <td width=\"186\"><img src=\"imagenes/logo_grande.jpg\"></td>
	          <td class=\"td_sup_top\" align=\"center\" valign=\"middle\">
			  <a href=\"https://turismo.mogan.es\" target=\"_blank\"><img src=\"imagenes/".$idioma."/banner.gif\" border=\"0\" alt=\"Ir a http://turismo.mogan.es\"></a>
			  </td>
	          <td width=\"190\" class=\"td_sup_top\" align=\"center\" valign=\"middle\"><img src=\"imagenes/logo_ges.gif\" border=\"0\" alt=\"\"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	  <tr>
	  	<td class=\"td_inf_top\" height=\"20%\ align=\"middle\" >&nbsp;&nbsp;
");

print("<a class=\"txt_idioma\" href=\"\" onclick=\"parent.document.location.href='index.php?idioma=".$idioma."';\">             
         &nbsp; <img src='imagenes/ic_home.png' border='0' alt=''> &nbsp;
       </a> ");

if ($idioma=="esp")
	print("
		<span class=\"txt_idiomaactual\"> 
		  &nbsp; <img src=\"imagenes/spanish.png\" border=\"0\" alt=\"\"> &nbsp; 
		</span> 
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php?idioma=eng'\">
		  &nbsp; <img src=\"imagenes/english.png\" border=\"0\" alt=\"\"> &nbsp;
		</a>
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php?idioma=deu'\">
		  &nbsp; <img src=\"imagenes/german.png\" border=\"0\" alt=\"\"> &nbsp;
		</a>
	");
else if ($idioma=="eng")
	print("
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php'\">
		  &nbsp; <img src=\"imagenes/spanish.png\" border=\"0\" alt=\"\"> &nbsp;
		</a> 
		<span class=\"txt_idiomaactual\">
		  &nbsp; <img src=\"imagenes/english.png\" border=\"0\" alt=\"\"> &nbsp; 
		</span> 
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php?idioma=deu'\">
		  &nbsp; <img src=\"imagenes/german.png\" border=\"0\" alt=\"\"> &nbsp;
		</a>
	");
else if ($idioma=="deu")
	print("
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php'\">
		  &nbsp; <img src=\"imagenes/spanish.png\" border=\"0\" alt=\"\"> &nbsp;
		</a>
		<a class=\"txt_idioma\" href=\"\" onclick=\"top.document.location.href='index.php?idioma=eng'\">
		  &nbsp; <img src=\"imagenes/english.png\" border=\"0\" alt=\"\"> &nbsp;
		</a> 
		<span class=\"txt_idiomaactual\">
		  &nbsp; <img src=\"imagenes/german.png\" border=\"0\" alt=\"\"> &nbsp; 
		</span> 
	");

print("
		</td>
	    <td class=\"td_inf_top\" height=\"20%\" align=\"right\">
<font class=\"txt_idioma\">
	            <script type=\"text/javascript\">
	var d=new Date()
");

if ($idioma=="esp") 
	print("
		var weekday=new Array(\"Domingo\",\"Lunes\",\"Martes\",\"Mi&#233;rcoles\",\"Jueves\",\"Viernes\",\"S&#225;bado\")
		var monthname=new Array(\"Enero\",\"Febrero\",\"Marzo\",\"Abril\",\"Mayo\",\"Junio\",\"Julio\",\"Agosto\",\"Septiembre\",\"Octubre\",\"Noviembre\",\"Diciembre\")
	");
else if ($idioma=="eng")
	print("
		var weekday=new Array(\"Sunday\",\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\")
		var monthname=new Array(\"January\",\"February\",\"March\",\"April\",\"May\",\"June\",\"July\",\"August\",\"September\",\"October\",\"November\",\"December\")	
	");
else if ($idioma=="deu")
	print("
		var weekday=new Array(\"Sonntag\",\"Montag\",\"Dienstag\",\"Mittwoch\",\"Donnerstag\",\"Freitag\",\"Samstag\")
		var monthname=new Array(\"Januar\",\"Februar\",\"März\",\"April\",\"Mai\",\"Juni\",\"Juli\",\"August\",\"September\",\"Oktober\",\"November\",\"Dezember\")
	");

print("
	document.write(weekday[d.getDay()] + \" \")
	document.write(d.getDate() + \"".(($idioma=="esp")?" de ":" ")."\")
	document.write(monthname[d.getMonth()] + \" \")
	document.write(d.getFullYear())
	</script>
	            | 
	            <script type=\"text/javascript\">
	var d = new Date();
	if(d.getHours()<10) document.write(\"0\"+d.getHours());
	else document.write(d.getHours());
	document.write(\":\");
	if(d.getMinutes()<10) document.write(\"0\"+d.getMinutes());
	else document.write(d.getMinutes());
	document.write(\" h\")
	</script>
</font>
	<img src=\"imagenes/trans.gif\" width=\"30\" height=\"1\"></td>
	  </tr>
	</table>
	</body>
	</html>
");

?>