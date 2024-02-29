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
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">
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
		  <font class=\"txt_cabecera\">".$IDI_CAB_TRAM."</font><br><img src=\"../../imagenes/cabecera.gif\" width=\"337\" height=\"29\" border=\"0\" alt=\"\">
	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_border.gif\" align=\"center\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>

				<div class=\"txt_generico\" align=\"left\">
				Mediante estos enlaces podrá acceder a los formularios y rellenarlos directamente 
				desde su ordenador. Una vez rellenados, puede imprimirlos y presentarlos en el Ayuntamiento	para 
				su posterior tramitación.<br><br>
				</div>
				<div align=\"center\">
				<a href=\"generico_form.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_ordenanza_link\"><img src=\"../../imagenes/topito.gif\" width=\"6\" height=\"6\" border=\"0\" hspace=\"4\">Modelo genérico de solicitud</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href=\"alta_form.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_ordenanza_link\"><img src=\"../../imagenes/topito.gif\" width=\"6\" height=\"6\" border=\"0\" hspace=\"4\">Alta/baja de domiciliación bancaria</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<!-- <a href=\"baja_form.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_ordenanza_link\"><img src=\"../../imagenes/topito.gif\" width=\"6\" height=\"6\" border=\"0\" hspace=\"4\">Baja de domiciliación bancaria</a> -->
				</div>
			  </td>
	          <td background=\"../../imagenes/1_pod_right_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	        </tr>
	        <tr>
	          <td><img src=\"../../imagenes/1_pod_bottom_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td background=\"../../imagenes/1_pod_bottom_border.gif\" align=\"center\"><img src=\"../../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../../imagenes/1_pod_bottom_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	      </table>
	      <br>

	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_border.gif\" align=\"center\"><img src=\"../../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr> 
	          <td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td class=\"td_titulo\"><span class=\"txt_seccion_ordenanzas\">".$IDI_NOT1."<br></span>
			    <span class=\"txt_normal\">".$IDI_NOT2."<br>
	            </span></td>
	          <td background=\"../../imagenes/1_pod_right_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	        </tr>
	        <tr> 
	          <td><img src=\"../../imagenes/1_pod_bottom_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td background=\"../../imagenes/1_pod_bottom_border.gif\" align=\"center\"><img src=\"../../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../../imagenes/1_pod_bottom_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	      </table>

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
	</body>
	</html>

");

?>
