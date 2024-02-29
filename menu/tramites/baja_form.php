<?php

// Recoger el parámetro que indica el idioma seleccionado
if ($HTTP_GET_VARS["idioma"]!="") $idioma=$HTTP_GET_VARS["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "../../idioma/idioma_".$idioma.".php";

// Cargar el fichero con el texto del formulario
include "baja_text.php";

print("

	<html>
	<head>
	<title>Untitled Document</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../css/estilos.css\" type=\"text/css\">
	</head>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">

<form name=\"form\" action=\"baja_word.php\" method=\"post\">

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

<div align=\"center\" class=\"txt_seccion_ordenanzas\">BAJA DOMICILIACION BANCARIA</div>
<table><tr>
<td align=\"center\" width=\"100\"><img src=\"../../imagenes/logo_mogan_form.gif\" align=\"middle\"></td>
<td><font style=\"font-family: arial; font-size: 22px; font.weight: bold;\">ILTRE. AYUNTAMIENTO DE MOGAN</font></td>
</tr></table>
<br>

<div class=\"txt_formulario\" align=\"center\">
".$alta00."<br><br>
<table class=\"txt_formulario\" style=\"font-weight: bold;\">
<tr>
<td>".$alta01."</td>
<td><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"01\"></td>
<td width=\"40px\" rowspan=\"4\"></td>
<td>".$alta02."</td>
<td><input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"02\"></td>
</tr>
<tr>
<td>".$alta03."</td>
<td><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"03\"></td>
<td>".$alta04."</td>
<td><input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"04\"></td>
</tr>
<tr>
<td>".$alta05."</td>
<td><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"05\"></td>
<td>".$alta06."</td>
<td><input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"06\"></td>
</tr>
<tr>
<td>".$alta07."</td>
<td><input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"07\"></td>
<td>".$alta08."</td>
<td><input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"08\"></td>
</tr>
</table>
</div>
<br><br>

<div class=\"parrafo_formulario\">
".$alta09."<br><br>
<table align=\"center\" class=\"txt_formulario\" style=\"font-weight: bold;\">
<tr>
<td align=\"center\">".$alta10."</td>
<td rowspan=\"2\" width=\"30px;\"></td>
<td align=\"center\">".$alta11."</td>
<td rowspan=\"2\" width=\"30px;\"></td>
<td align=\"center\">".$alta12."</td>
<td rowspan=\"2\" width=\"30px;\"></td>
<td align=\"center\">".$alta13."</td>
</tr>
<tr>
<td align=\"center\"><input maxlength=\"4\" style=\"width: 50px;\" type=\"text\" class=\"caja_formulario\" name=\"10\"></td>
<td align=\"center\"><input maxlength=\"4\" style=\"width: 50px;\" type=\"text\" class=\"caja_formulario\" name=\"11\"></td>
<td align=\"center\"><input maxlength=\"2\" style=\"width: 50px;\" type=\"text\" class=\"caja_formulario\" name=\"12\"></td>
<td align=\"center\"><input maxlength=\"10\" style=\"width: 150px;\" type=\"text\" class=\"caja_formulario\" name=\"13\"></td>
</tr>
</table>
</div>
<br>

<div class=\"parrafo_formulario\">
<b>".$alta14."</b>&nbsp;<input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"14\">
<br><br>
</div>
<div class=\"parrafo_formulario\">
<b>".$alta15."</b>&nbsp;<input style=\"width: 200px;\" type=\"text\" class=\"caja_formulario\" name=\"15\">
&nbsp;&nbsp;
<b>".$alta16."</b>&nbsp;<input style=\"width: 50px;\" type=\"text\" class=\"caja_formulario\" name=\"16\">
&nbsp;&nbsp;
<b>".$alta17."</b>&nbsp;<input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"17\">
<br><br><br>
</div>

<div class=\"parrafo_formulario\">
".$alta18."
<br><br>
</div>
<div class=\"parrafo_formulario\">
<b>".$alta19."</b>&nbsp;<input style=\"width: 200px;\" type=\"text\" class=\"caja_formulario\" name=\"19\">
&nbsp;&nbsp;
<b>".$alta20."</b>&nbsp;<input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"20\">
<br><br>
</div>

<table align=\"center\" class=\"txt_formulario\" style=\"font-weight: bold;\">
<tr>
<td align=\"center\">".$alta21."</td>
<td rowspan=\"5\" width=\"20px;\"></td>
<td align=\"center\">".$alta22."</td>
</tr>
<tr>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"21A\"></td>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"21B\"></td>
</tr>
<tr>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"22A\"></td>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"22B\"></td>
</tr>
<tr>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"23A\"></td>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"23B\"></td>
</tr>
<tr>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"24A\"></td>
<td align=\"center\"><input style=\"width: 300px;\" type=\"text\" class=\"caja_formulario\" name=\"24B\"></td>
</tr>
</table>
<br><br>

<div class=\"parrafo_formulario\">
".$alta23."
<br><br>
</div>

<div class=\"parrafo_formulario\">
".$alta25."&nbsp;<input style=\"width: 20px;\" type=\"text\" class=\"caja_formulario\" name=\"25\">
&nbsp;&nbsp;&nbsp;
".$alta26."&nbsp;<input style=\"width: 100px;\" type=\"text\" class=\"caja_formulario\" name=\"26\">
&nbsp;&nbsp;&nbsp;
".$alta27."&nbsp;<input style=\"width: 20px;\" type=\"text\" class=\"caja_formulario\" name=\"27\">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
".$alta28."
</div>

<div class=\"parrafo_formulario\" style=\"font-size: 10px;\">
<br><br><b>".$alta29."</b><br>
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
