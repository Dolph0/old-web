<?php
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "../../idioma/idioma_".$idioma.".php";

print("

	<html>
	<head>
	<title>Untitled Document</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../css/estilos.css\" type=\"text/css\">
    <script type=\"text/javascript\" src=\"../../js/antispam.js\"></script>

	<script language=\"JavaScript\">

		function enviar() {

			if (document.getElementById('identificacion').value=='' || !IsNumeric(document.getElementById('identificacion').value)  )
				alert('Introduzca un n\u00FAmero de identificaci\u00F3n v\u00E1lido.');
			else
			if (document.getElementById('referencia').value=='' || !IsNumeric(document.getElementById('referencia').value) )
				alert('Introduzca un n\u00FAmero de referencia v\u00E1lido.');
			else
			if (document.getElementById('importe').value=='')
				alert('Introduzca un importe.');
			else
				if (!IsEuro(document.getElementById('importe').value))
					alert('Introduzca un importe v\u00E1lido.');
			else return true;

			return false;
		}

		function IsNumeric(strString)  {
		   var strValidChars = '0123456789';
		   var strChar;
		   var blnResult = true;

		   if (strString.length == 0) return false;

		   for (i = 0; i < strString.length && blnResult == true; i++)     {
		      strChar = strString.charAt(i);
		      if (strValidChars.indexOf(strChar) == -1)  {
		         blnResult = false;
	         }
	      }
		  return blnResult;
	   }

	   function IsEuro(dato) {
		 // Esta funcion recibe por parametros un numero decimal o entero, y comprueba que sea correcto.
		 // Hay 2 formatos validos para los numeros decimales: 456,89 y 12.33 por ejemplo.
		 // Es decir, la coma decimal puede ser , o .
		 // SOLO PERMITIMOS DOS DECIMALES

		  if ( !dato.match('^[0-9]+[,.]?[0-9]{0,2}$') ) {
			if ( !IsNumeric(dato) ) {
				return false;
			}
		  }
		  return true;
		}


	</script>

	</head>

	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
	  <tr>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	    <td valign=\"bottom\"><a href=\"../../centro_home.php?idioma=".$idioma."\" class=\"txt_miga_link\" target=\"contenido\">".$IDI_INIC."</a>
		 | <span class=\"txt_miga_no_link\">".$IDI_CAB_PAG."</span>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	    <td height=\"100%\" valign=\"top\"> <br>
		  <font class=\"txt_cabecera\">".$IDI_CAB_PAG."</font><br><img src=\"../../imagenes/cabecera.gif\" width=\"337\" height=\"29\" border=\"0\" alt=\"\">
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
					<span style=\"font-weight: bold; font-size: 14px;\"></span>
					<font class=\"txt_cabecera\">CON CERTIFICADO DIGITAL&nbsp;</font>
					 <a href='https://oat.mogan.es:8448/ventanilla/web/inicioWebc.do?opcion=noreg' target='_blank'><img src=\"../../imagenes/e-admon3.jpg\" ></a>
					</center>  <br><br>
				</div>
			<hr>
			<div class=\"txt_generico\" align=\"left\">
				<br/><font class=\"txt_cabecera\">SIN CERTIFICADO DIGITAL&nbsp;</font><br/><br/>

				<div class=\"txt_generico\" align=\"left\">
				<span style=\"font-weight: bold; font-size: 14px;\">1º)</span> Señale el impuesto a pagar introduciendo
				la IDENTIFICACION y la REFERENCIA y el IMPORTE del pago. Encontrar&#225; estos datos impresos en el documento que el Ayuntamiento ha enviado a su domicilio. <br><br>
				<form name=\"form\" method=\"post\">
				<table align=\"center\" style=\"font-family: arial; font-size: 12px; color: black; font-weight: bold;\">
				<tr>
				<td align=\"right\">REFERENCIA<br>(12 d&#237;gitos)</td>
				<td><input type=\"text\" size=\"20\" maxlength=\"20\" id=\"referencia\" name=\"referencia\" value=\"".$_POST['referencia']."\"></td>
				<td rowspan=3 width=\"33\"><img src=\"../../imagenes/ejemplo.png\" ></td>
				</tr>
				<tr>
				<td align=\"right\">IDENTIFICACION<br>(10 d&#237;gitos)</td>
				<td><input type=\"text\" size=\"20\" maxlength=\"20\" id=\"identificacion\"  name=\"identificacion\" value=\"".$_POST['identificacion']."\"></td>
				</tr>
				<tr>
				<td align=\"right\">IMPORTE<br>(Formatos v&#225;lidos: 1234,56 o 1234.56)</td>
				<td><input type=\"text\" size=\"10\" maxlength=\"10\" id=\"importe\" name=\"importe\">&nbsp;€</td>
				</tr>
				</table>
				</form>
				<br>
				<div class=\"txt_generico\" align=\"left\">
					<span style=\"font-weight: bold; font-size: 14px;\">2º)</span> a) Si desea pagar mediante tarjeta bancaria.<br>
				</div>
				</div>

				<div style=\"font-family: arial; font-size: 12px; color: black; font-weight: bold;\" align=\"center\">
        <table  cellpadding='10' border=0>
        <tr><td>
				<span border=1 style=\"cursor: pointer;\" onclick=\"if (enviar()) { window.open('./pago_tarjeta_TPVBBVA.php?importe='+form.importe.value+'&identificacion='+form.identificacion.value+'&referencia='+form.referencia.value); }\"><img src=\"../../imagenes/entidades/MasterCard1.jpg\" border=\"0\">&nbsp;<img src=\"../../imagenes/entidades/Maestro1.jpg\" border=\"0\">&nbsp;<img src=\"../../imagenes/entidades/Visa1.jpg\" border=\"0\">&nbsp;<img src=\"../../imagenes/entidades/VisaElectron1.jpg\" border=\"0\"></span>
	       </td>
		</table>
        <br>
				<div class=\"txt_generico\" align=\"left\">
					<span style=\"font-weight: bold; font-size: 12px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Si lo desea también puede realizar el trámite o pago a través de la banca electrónica de su entidad bancaria si es entidad colaboradora del Ayuntamiento de Mogán, para lo que ha de entrar a través de la página web de su banco.</span>
				</div>
                <br>
                <br>
		<tr>
				<td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
				<td>
				<div class=\"txt_generico\" align=\"left\"><span style=\"font-weight: bold; font-size: 14px;\"></span>3º) Conserve el documento que le emite la entidad colaboradora.<br><br>Cualquiera que sea la modalidad de pago utilizada, podr&#225; solicitar la Carta de Pago transcurridas al menos 24 horas desde la transacci&#243;n, en las Oficinas de Recaudaci&#243;n del Ayuntamiento de Mog&#225;n:  <br>
				<center><table><tr>
				<td><span class=\"txt_generico\">
				Avda. de la Constituci&oacute;n 14<br>
				35140 Mog&#225;n<br>
				Telf.: 928 15 88 06<br>
				Fax.: 928 56 85 12<br>
				</span>
			    </td>
				<td>&nbsp;&nbsp;</td>
				<td>
	            <span class=\"txt_generico\">
				Calle Tamar&#225;n 4<br>
				35120 Arguinegu&#237;n<br>
				Telf.: 928 56 85 66<br>
				Fax.: 928 73 50 04<br>
				</span>
				</td>
				</tr>
				<tr><td colspan=4 align='center'><span class=\"txt_generico\" >Correo electr&#243;nico <span id=\"correo\"></span></span></td></tr>
				</table></center>
				</span></div>
				</tr></td>
				</div>
			  </td>
        </tr>
	        <tr>
	          <td><img src=\"../../imagenes/1_pod_bottom_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td background=\"../../imagenes/1_pod_bottom_border.gif\" align=\"center\"><img src=\"../../imagenes/trans.gif\" width=\"24\" height=\"24\"></td>
	          <td><img src=\"../../imagenes/1_pod_bottom_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	      </table>
	      <br>
	    </td>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	  <tr>
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
    <td valign=\"bottom\" height=\"33\" align=\"center\" class=\"td_firma\">Mog&#225;n Gesti&#243;n Municipal,
	Telf: 928 15 88 06<br>
      ".$IDI_DESA." </td>
	    <td width=\"33\" class=\"td_firma\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	  </tr>
	</table>

	</body>
	</html>

");