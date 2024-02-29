<?php

// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "../../idioma/idioma_".$idioma.".php";

// Cargar el fichero con el texto del formulario
include "generico_text.php";

print("

	<html>
	<head>
	<title>Untitled Document</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../css/estilos.css\" type=\"text/css\">
	</head>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">

	<form name=\"form\" action=\"generico_word.php\" method=\"post\">

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
<td align=\"right\">
<img src=\"../../imagenes/imprimir_exp.gif\" border=\"0\" alt=\"\">
</td>
</tr></table>
<div align=\"right\">
<input style=\"width: 100px; font-family: arial; font-size: 11px; font-weight: bold;\" type=\"submit\" value=\"Continuar >>>\">
<br><br>
</div>

	      <table bgcolor=\"white\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../../imagenes/1_pod_l_cornerB.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_borderB.gif\" align=\"center\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_cornerB.gif\" width=\"23\" height=\"24\"></td>
        </tr>
	        <tr>
	          <td background=\"../../imagenes/1_pod_left_borderB.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>

<div align=\"center\" class=\"txt_seccion_ordenanzas\">MODELO GENERICO DE SOLICITUD</div>
<table><tr>
<td align=\"center\" width=\"100\"><img src=\"../../imagenes/logo_mogan_form.gif\" align=\"middle\"></td>
<td><font style=\"font-family: arial; font-size: 22px; font.weight: bold;\">ILTRE. AYUNTAMIENTO DE MOGAN</font></td>
</tr></table>
<br>

<div class=\"parrafo_formulario\">
".$generico00."&nbsp;<input style=\"width: 600px;\" type=\"text\" class=\"caja_formulario\" name=\"P00\">
<br><br>
".$generico02."&nbsp;<select class=\"combo_formulario\" name=\"P02\"><option value=\"DNI\">DNI</option><option value=\"Tarjeta de residencia\">Tarjeta de residencia</option><option value=\"Pasaporte\">Pasaporte</option></select>
".$generico03."&nbsp;<input style=\"width: 455px;\" type=\"text\" class=\"caja_formulario\" name=\"P000\">
<br><br>
".$generico01."&nbsp;<input style=\"width: 530px;\" type=\"text\" class=\"caja_formulario\" name=\"P01\">
<br><br>
".$generico02."&nbsp;<select class=\"combo_formulario\" name=\"P012\"><option value=\"DNI\">DNI</option><option value=\"Tarjeta de residencia\">Tarjeta de residencia</option><option value=\"Pasaporte\">Pasaporte</option></select>
".$generico03."&nbsp;<input style=\"width: 455px;\" type=\"text\" class=\"caja_formulario\" name=\"P03\">
<br><br>
".$generico04."&nbsp;<input style=\"width: 395px;\" type=\"text\" class=\"caja_formulario\" name=\"P04\">
<br><br>".$generico05."&nbsp;<input style=\"width: 270px;\" type=\"text\" class=\"caja_formulario\" name=\"P05\">
".$generico06."&nbsp;<input style=\"width: 110px;\" type=\"text\" class=\"caja_formulario\" name=\"P06\">
".$generico07."&nbsp;<input style=\"width: 110px;\" type=\"text\" class=\"caja_formulario\" name=\"P07\">
<br><br><br>
</div>

<div class=\"parrafo_formulario\">
".$generico08."<br><br>
<table border=0>
<td class=\"parrafo_formulario\" valign=\"top\">".$generico09."&nbsp;</td>
<td><textarea class=\"textarea_formulario\" name=\"P09\" cols=\"88\" rows=\"8\"></textarea></td></table>
<br>
</div>

<div class=\"parrafo_formulario\">
".$generico10."<br><br>
<table border=0>
<td class=\"parrafo_formulario\" valign=\"top\">".$generico11."&nbsp;</td>
<td><textarea class=\"textarea_formulario\" name=\"P11\" cols=\"79\" rows=\"3\"></textarea></td></table>
<br><br>
</div>

<div class=\"parrafo_formulario\">
".$generico12."<br><br>
<textarea class=\"textarea_formulario\" name=\"P12\" cols=\"93\" rows=\"3\"></textarea>
<br><br>
</div>

<center>
<div class=\"parrafo_formulario\">
".$generico13."&nbsp;<input style=\"width: 20px;\" type=\"text\" class=\"caja_formulario\" name=\"P13\">
".$generico14."&nbsp;<input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"P14\">
".$generico15."<input style=\"width: 20px;\" type=\"text\" class=\"caja_formulario\" name=\"P15\">
<br><br><br><br>
</div>

<div class=\"parrafo_formulario\">
".$generico16."&nbsp;__________________________________
<br><br><br><br>
</div>

<div class=\"parrafo_formulario\">
".$generico17."
</div>
</center>

<br>
<div class=\"parrafo_formulario\">
".$generico18."
</div>

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

</form>

	  <tr> 
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
    <td valign=\"bottom\" height=\"33\" align=\"center\" class=\"td_firma\">Mogán Gestión Municipal, 
	Telf: 928 15 88 06<br>
      ".$IDI_DESA." </td>
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	</table>



	</body>
	</html>

");

?>
