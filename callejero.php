<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php 
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "idioma/idioma_".$idioma.".php";
?>
<html>
<head>
	<title>Callejero Mog&#225;n</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="css/estilos.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" class="fondo_body_contenido" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
	  <tbody>
	  <tr>
            <td width="33"><img src="imagenes/trans.gif" width="33" height="20"></td>
            <td valign="bottom"><a href="centro_home.php?idioma="<?php echo "esp"; ?>" class="txt_miga_link" target="contenido"><?php echo $IDI_INIC?>
              </a> | <span class="txt_miga_no_link"><?php echo $IDI_CALL?> MOG&#193;N</span></td>
            <td width="33"><img src="imagenes/trans.gif" width="33" height="20"></td>
          </tr>
	  <tr>
	    <td width="33"><img src="imagenes/trans.gif" width="33" height="33"></td>
	    <td valign="top"> <br>
		  <font class="txt_cabecera"><?php echo $IDI_CALL?> MOG&#193;N</font><br><img src="imagenes/cabecera.gif" width="337" height="29" border="0" alt="">

	      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	        <tbody><tr>
	          <td><img src="imagenes/1_pod_l_corner.gif" width="23" height="24"></td>
	          <td width="100%" background="imagenes/1_pod_top_border.gif" align="center"><img src="imagenes/trans.gif" width="24" height="24"></td>
	          <td><img src="imagenes/1_pod_r_corner.gif" width="23" height="24"></td>
	        </tr>
            <tr height="600" >
		<td background="imagenes/1_pod_left_border.gif" valign="middle"><img src="imagenes/trans.gif" width="23" height="23"></td>
                <td><iframe frameborder="0" height="600px" src="https://visor.grafcan.es/ol3/grafcan/embed_mun.php?mun=35012" width="100%"></iframe></td>
		<td background="imagenes/1_pod_right_border.gif" valign="middle"><img src="imagenes/trans.gif" width="23" height="23"></td>    
            </tr>
	        <tr>
	          <td><img src="imagenes/1_pod_bottom_l_corner.gif" width="23" height="24"></td>
	          <td background="imagenes/1_pod_bottom_border.gif" align="center"><img src="imagenes/trans.gif" width="24" height="24"></td>
	          <td><img src="imagenes/1_pod_bottom_r_corner.gif" width="23" height="24"></td>
	        </tr>
	      </tbody></table>

	      
	      <br>
	    </td>
	    <td width="33"><img src="imagenes/trans.gif" width="33" height="33"></td>
	  </tr>
	  <tr>
	    <td width="33" class="td_firma"><img src="imagenes/trans.gif" width="33" height="33"></td>
    <td valign="bottom" height="33" align="center" class="td_firma">Mog&#225;n Gesti&#243;n Municipal,
	Telf: 928 15 88 06<br>
       </td>
	    <td width="33" class="td_firma"><img src="imagenes/trans.gif" width="33" height="33"></td>
	  </tr>
    </tbody>
</table>
</body>
</html>
