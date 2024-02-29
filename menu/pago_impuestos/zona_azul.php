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
    <script type=\"text/javascript\" src=\"../../js/zona_azul.js\"></script>

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
		 | <span class=\"txt_miga_no_link\">".$IDI_CAB_ZON."</span>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"33\"></td>
	    <td height=\"100%\" valign=\"top\"> <br>
		  <font class=\"txt_cabecera\">".$IDI_CAB_ZON."</font><br><img src=\"../../imagenes/cabecera.gif\" width=\"337\" height=\"29\" border=\"0\" alt=\"\">
	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr>
	          <td><img src=\"../../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_border.gif\" align=\"center\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>
			<div class=\"txt_generico\" align=\"center\">
				<br/><font class=\"txt_cabecera\">DATOS DEL BOLET&#205;N DE DENUNCIA&nbsp;</font><br/><br/>

				<div class=\"txt_generico\" align=\"center\">
    				Introduzca los datos solicidados que se encuentran en el bolet&#237;n de denuncia<br><br>
                    <form name=\"form\" method=\"post\">
						<img src=\"../../imagenes/ejemplo_ticket_zona_azul.png\" ><br><br>
        				<table align=\"center\" style=\"font-family: arial; font-size: 15px; color: black; font-weight: bold;\">
						<tr>
        				<td align=\"right\">EXPEDIENTE:</td>
        				<td><input type=\"text\" size=\"14\" maxlength=\"10\" id=\"expediente\" oninput=\"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');\"  onkeyup=\"estadoBotonEnviar();\" name=\"expediente\" value=\"\"></td>
						</tr>
        				<tr>
        				<td align=\"right\">MATR&#205;CULA:</td>
        				<td><input type=\"text\" size=\"14\" maxlength=\"10\" id=\"matricula\" oninput=\"this.value = this.value.replace(/[^0-9a-zA-Z]+$/g, '').replace(/(\..*)\./g, '$1').toUpperCase();\" onkeyup=\"estadoBotonEnviar();\" name=\"matricula\" value=\"\"></td>
        				</tr>
        				</table>
    				</form>
				</div>

				<div style=\"font-family: arial; font-size: 12px; color: black; font-weight: bold;\" align=\"center\" id=\"div_boton_enviar_formulario\">
        <table  cellpadding='10' border=0>
        <tr>
            <td>
				<!-- LA CAJA DE CANARIAS -->
				<!-- <span style=\"cursor: pointer;\" onclick=\"if (enviar()) { form.action='lacajadecanarias/envio.php'; form.submit(); }\"><img src=\"../../imagenes/entidades/lacajadecanarias.jpg\" border=\"0\"></span> -->
				<!-- TARJETA -->
				<span border=1>
                    <button id=\"boton_enviar_formulario\" onclick=\"enviarDatosBoletin()\" disabled>Enviar</button>
                </span>
			</td>
        </table>
		<tr>
				<td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
				<td>
				<div class=\"txt_generico\" align=\"center\"><span style=\"font-weight: bold; font-size: 14px;\"></span> Importe de la liquidaci&#243;n 4,45&#8364;.<br><br>
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