<?php
session_start();
if (!isset ($_SESSION[codisesi])) die; 

// Conexión a la base de datos
include "../database/func.inc";

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

$query="SELECT ds_response_in, ds_merchantdata_in, ds_authorisationcode_in FROM regioper WHERE sesion_id='$_SESSION[codisesi]' AND ds_authorisationcode_in!='' " ;
$data=Sql($query);
$printlink=0; 
echo "<center>";
if ($data['ds_response_in']!="") {
  /* Valores para ds_response_in
  CÓDIGO			SIGNIFICADO 
  0000 a 0099		Transacción autorizada para pagos y preautorizaciones
  0900			Transacción autorizada para devoluciones y confirmaciones
  101				Tarjeta caducada
  102				Tarjeta en excepción transitoria o bajo sospecha de fraude
  104/9104		Operación no permitida para esa tarjeta o terminal
  */
  $ds_response_in =  intval($data['ds_response_in']);
  if ($ds_response_in >= 0 && $ds_response_in <= 99) {
	echo "<h3>La transacci&oacute;n <b>".$data['ds_authorisationcode_in']."</b> correspondiente al pago del documento: <br><b>".$data['ds_merchantdata_in']."</b><br><br>Ha sido registrada en el sistema&nbsp; ".date('d-m-Y H:i')."<br><br>Conserve este comprobante como  justificante de pago a efectos probatorios.<br><br>Si desea obtener la Carta de Pago, podrá solicitarla, transcurridas al menos 24 horas desde la transacción,<br>en las oficinas de Recaudaci&oacute;n de Mog&aacute;n Gesti&oacute;n Municipal<br><br>";
	$printlink=1;
  } else {
	echo "<h3>El documento de referencia no puede ser abonado mediante este medio<br><br>Por favor contacte con el Departamento de Recaudaci&oacute;n de Mog&aacute;n Gesti&oacute;n Municipal<br><br>";
  }
} else {
	echo "<h3>Se ha producido un error en la transacci&oacute;n<br><br>Por favor contacte con el Departamento de Recaudaci&oacute;n de Mog&aacute;n Gesti&oacute;n Municipal<br><br>";
}
echo "<table><tr>
				<td>
				Avda. de la Constituci&oacute;n 14<br>
				35140 Mogán<br>
				Telf.: 928 15 88 06<br>
				Fax.: 928 56 85 12<br>
			    </td>
				<td>&nbsp;&nbsp;</td>
				<td>
				Calle Tamarán 4<br>
				35120 Arguineguín<br>
				Telf.: 928 56 85 66<br>
				Fax.: 928 73 50 04<br>
				</td>
				</tr>
				<tr>
					<td colspan=2 align='center'>Correo electrónico <a href='mailto:gestionmunicipal@gestmogan.com'> gestionmunicipal@gestmogan.com</a></td>
				</tr>
			</table>";
echo "<br>";
echo "<input type='button' value='Volver' onclick='window.close()';>";
if ($printlink==1) {
	echo "<input type='button' id='printlink' value='Imprimir' onclick='window.print()';>";
}
echo "</center>";

session_destroy();
?>
