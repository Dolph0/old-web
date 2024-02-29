<?php
session_start();
if (!isset ($_SESSION[codisesi])) die; // 

// Recoger el parámetro que indica el idioma seleccionado
if ($HTTP_GET_VARS["idioma"]!="") $idioma=$HTTP_GET_VARS["idioma"]; else $idioma="esp";

include "../../../idioma/idioma_".$idioma.".php";

print("
	<html>
	<head>
	<title></title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../../css/estilos.css\" type=\"text/css\">

	<style type='text/css' media='print'>
		#returnlink {display : none}
		#printlink {display : none}
	</style>
	</head>
	
	<body bgcolor=\"#FFFFFF\" text=\"#000000\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
	  <tr>
	    <td colspan=\"2\" valign=\"bottom\"> 
	      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
	        <tr> 
	          <td width=\"186\"><img src=\"../../../imagenes/logo_grande.gif\" vspace=\"0\"></td>
	          <td class=\"td_sup_top\" align=\"center\" valign=\"middle\">
			  <a href=\"../../../menu/turismo_mogan.php?idioma=".$idioma."\" target=\"contenido\"><img src=\"../../../imagenes/".$idioma."/banner.gif\" border=\"0\"></a>
			  </td>
	          <td width=\"190\" class=\"td_sup_top\" align=\"center\" valign=\"middle\"><img src=\"../../../imagenes/logo_ges.gif\" border=\"0\" alt=\"\"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	  <tr> 
	    <td width=\"33\"><img src=\"../../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
     	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">
	    <td height=\"100%\" valign=\"top\"> <br>
	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr> 
	          <td><img src=\"../../../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../../imagenes/1_pod_top_border.gif\" align=\"center\"></td>
	          <td><img src=\"../../../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>
			  </td>	
			</tr>
	  <tr>
   </table>
");
echo "<center>";
echo "<h3>Se ha producido un error en la transacci&oacute;n<br><br>Por favor contacte con el Departamento de Recaudaci&oacute;n</h3><h4>Mogán Gestión Municipal Telf: 928 15 88 06<br><br></h4>";
echo "<input type='button' value='Volver' onclick='window.close()';>";
echo "</center>";
session_destroy();
?>
