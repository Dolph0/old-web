<?php

// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "../idioma/idioma_".$idioma.".php";

print("

	<html>
	<head>
	<title>$IDI_CAB_CON</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../css/estilos.css\" type=\"text/css\">
    <script type=\"text/javascript\" src=\"../js/antispam.js\"></script>
	</head>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
	  <tr>
	    <td width=\"33\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	    <td valign=\"bottom\"><a href=\"../centro_home.php?idioma=".$idioma."\" class=\"txt_miga_link\" target=\"contenido\">".$IDI_INIC." 
	      </a> | <span class=\"txt_miga_no_link\">".$IDI_CAB_CON."</span></td>
	    <td width=\"33\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
	    <td width=\"33\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	    <td height=\"100%\" valign=\"top\"> <br>
		  <font class=\"txt_cabecera\">".$IDI_CAB_CON."</font><br><img src=\"../imagenes/cabecera.gif\" width=\"337\" height=\"29\" border=\"0\" alt=\"\">
	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../imagenes/1_pod_top_border.gif\" align=\"center\"></td>
	          <td><img src=\"../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>
			  
				<span class=\"txt_subtitulo_ordenanzas\">Correo electrónico: </span>
				<span class=\"txt_normal\" id=\"correo\"></span> 
				<!--
				<input  type=\"button\" value=\"contactar\" onclick=\"\">
				-->
				</a>
				</span>
				<br><br>
				
	            <span class=\"txt_subtitulo_ordenanzas\">Mogán Gestión Municipal, S.L.</span><br>
	            <span class=\"txt_normal\">
				Avda. de la Constituci&oacute;n 14<br>
				35140 Mogán<br>
				Telf.: 928 15 88 06<br>
				Telfax.: 928 56 85 12<br>
				</span>
			    <br>
	            <span class=\"txt_subtitulo_ordenanzas\">Mogán Gestión Municipal, S.L.</span><br>
	            <span class=\"txt_normal\">
				Calle Tamarán 4<br>
				35120 Arguineguín<br>
				Telf.: 928 56 85 66<br>
				Telfax.: 928 73 50 04<br>
				</span>
				
			  </td>
	          <td background=\"../imagenes/1_pod_right_border.gif\" valign=\"middle\"><img src=\"../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	        </tr>
	        <tr>
	          <td><img src=\"../imagenes/1_pod_bottom_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td background=\"../imagenes/1_pod_bottom_border.gif\" align=\"center\"><img src=\"../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../imagenes/1_pod_bottom_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	      </table>
	      <br>
	    </td>
	    <td width=\"33\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	  <tr> 
	    <td width=\"33\" class=\"td_firma\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
    <td valign=\"bottom\" height=\"33\" align=\"center\" class=\"td_firma\">Mogán Gestión Municipal, 
	Telf: 928 15 88 06<br>
      ".$IDI_DESA." </td>
	    <td width=\"33\" class=\"td_firma\"><img src=\"../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	</table>
	</body>
	</html>

");