<?php

// Recoger el parámetro que indica el idioma seleccionado
if ($HTTP_GET_VARS["idioma"]!="") $idioma=$HTTP_GET_VARS["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "../../idioma/idioma_".$idioma.".php";

print("

	<html>
	<head>
	<title>Untitled Document</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../css/estilos.css\" type=\"text/css\">
	</head>
	
	<script language=\"JavaScript\">
");

print("var objetos=new Array(".count($objetos).");");
for ($a=0;$a<count($objetos);$a++)
	print("objetos[".$a."]=\"".$objetos[$a]."\";");

print("

	function CambioConcepto ( combo ) {
		
		var c1=document.getElementById('21A').value;
		var c2=document.getElementById('22A').value;
		var c3=document.getElementById('23A').value;
		var c4=document.getElementById('24A').value;
		var c=combo.value;
		var texto,ncombo;
		
		if (combo.name=='21A') ncombo='21BT';
		else if (combo.name=='22A') ncombo='22BT';
		else if (combo.name=='23A') ncombo='23BT';
		else if (combo.name=='24A') ncombo='24BT';
		
		if (!combo.selectedIndex) texto=''; else texto=objetos[combo.selectedIndex-1];
		document.getElementById(ncombo).innerText=((texto=='')?'':'INTRODUZCA ')+texto;
	}
	
	</script>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">

");

print("

<form name=\"form\" action=\"alta_word.php\" method=\"post\">

	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
	  <tr> 
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	    <td valign=\"bottom\">
		<a href=\"../../centro_home.php?idioma=".$idioma."\" class=\"txt_miga_link\" target=\"contenido\">".$IDI_INIC."</a> 
		| <span class=\"txt_miga_no_link\">".$IDI_CAB_TRAM."</span>
		 </td>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	    <td height=\"100%\" valign=\"top\"> <br>
<table width=\"100%\"><tr>
<td>
		  <font class=\"txt_cabecera\">".$IDI_CAB_TRAM."</font><br><img src=\"../../imagenes/cabecera.gif\" width=\"337\" height=\"29\" border=\"0\" alt=\"\">
</td>
</tr></table>


	      <table bgcolor=\"white\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../../imagenes/1_pod_l_cornerB.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_borderB.gif\" align=\"center\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_cornerB.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../../imagenes/1_pod_left_borderB.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>
<br>
 <embed src=\"../../formularios/formulario_domiciliacion.pdf\" type=\"application/pdf\" width=\"900\" height=\"800\"> 
<br>
			  </td>
	          <td background=\"../../imagenes/1_pod_right_borderB.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	        </tr>
	        <tr>
	          <td><img src=\"../../imagenes/1_pod_bottom_l_cornerB.gif\" width=\"23\" height=\"24\"></td>
	          <td background=\"../../imagenes/1_pod_bottom_borderB.gif\" align=\"center\"><img src=\"../../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../../imagenes/1_pod_bottom_r_cornerB.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	      </table>
	      <br>
	    </td>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>

	  <tr> 
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
    <td valign=\"bottom\" height=\"33\" align=\"center\" class=\"td_firma\">Mogán Gestión Municipal, 
	Telf: 928 15 88 06<br>
      ".$IDI_DESA." </td>
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	</table>

</form>

	</body>
	</html>

");

?>
