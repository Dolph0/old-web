<?php
// 20131219: Envío de datos al TPV vrtual de RedSys para pago mendiante tarjeta
include "./euro.fnc";
include "./func.inc";
include "./database/func.inc";

session_start();
$_SESSION['codisesi'] = Codigo_sesion();

// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

include "../../idioma/idioma_".$idioma.".php";

print("
	<html>
	<head>
	<title></title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link rel=\"stylesheet\" href=\"../../css/estilos.css\" type=\"text/css\">

	</head>

	<body bgcolor=\"#FFFFFF\" text=\"#000000\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
	  <tr>
	    <td colspan=\"2\" valign=\"bottom\">
	      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
	        <tr>
	          <td width=\"186\"><img src=\"../../imagenes/logo_grande.gif\" vspace=\"0\"></td>
	          <td class=\"td_sup_top\" align=\"center\" valign=\"middle\">
			  <a href=\"../../menu/turismo_mogan.php?idioma=".$idioma."\" target=\"contenido\"><img src=\"../../imagenes/".$idioma."/banner.gif\" border=\"0\"></a>
			  </td>
	          <td width=\"190\" class=\"td_sup_top\" align=\"center\" valign=\"middle\"><img src=\"../../imagenes/logo_ges.gif\" border=\"0\" alt=\"\"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	  <tr>
	    <td width=\"33\"><img src=\"../../imagenes/trans.gif\" width=\"33\" height=\"20\"></td>
	  </tr>
	  <tr>
     	<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"fondo_body_contenido\">
	    <td height=\"100%\" valign=\"top\"> <br>
	      <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	        <tr>
	          <td><img src=\"../../imagenes/1_pod_l_corner.gif\" width=\"23\" height=\"24\"></td>
	          <td width=\"100%\" background=\"../../imagenes/1_pod_top_border.gif\" align=\"center\"></td>
	          <td><img src=\"../../imagenes/1_pod_r_corner.gif\" width=\"23\" height=\"24\"></td>
	        </tr>
	        <tr>
	          <td background=\"../../imagenes/1_pod_left_border.gif\" valign=\"middle\"><img src=\"../../imagenes/trans.gif\" width=\"23\" height=\"23\"></td>
	          <td>
			  </td>
			</tr>
	  <tr>
   </table>
");

// Comprobamos que los datos identificativos son correctos
$Emisora		= '350126'; // El valor no está almacenado. Se pasa como literal en todas las páginas
if (strlen(trim($_GET['referencia']))==12) {
	$Referencia		= substr($_GET['referencia'],0,10);
	$CC				= substr($_GET['referencia'],10,2);
} else {
	$Referencia		= "";
	$CC				= "";
}
$Identificacion	= $_GET['identificacion'];
$Importe		= euro2cent($_GET['importe']);
if (!VerificarDCReferenciaCobros($Emisora,$Referencia,$Identificacion,$Importe,$CC)) {
	echo "<center>";
	echo "<h3>Los valores introducidos son incorrectos<br>Imposible efectuar la transacci&oacute;n<br></h3>";
	echo "<input type='button' value='Volver' onclick='window.close()';>";
	echo "</center>";
	die();
}

// Comprobanos que este documento no figura abonado mediante tarjeta
// Es la única verificación posible dado que, si se ha pagado por otro medio, no existirá la información en esta BD
$query="SELECT regioper.numeoper FROM regioper WHERE regioper.idenno60='".$_GET['identificacion']."' AND regioper.refeno60='".$_GET['referencia']."' AND regioper.impo='".$_GET['importe']."' AND ds_response_in BETWEEN '0000' AND '0099' ";
$data=Sql($query);
if ($data!="") {
	echo "<center>";
	echo "<h3>El documento de referencia no puede ser abonado mediante este medio<br><br>Por favor contacte con el Departamento de Recaudaci&oacute;n</h3><h4>Mogán Gestión Municipal Telf: 928 15 88 06<br><br></h4>";
	echo "<input type='button' value='Volver' onclick='window.close()';>";
	echo "</center>";
	die();
}

// Valores constantes facilitados por el banco
// ENTORNO DE PRUEBAS
//$url_tpvv='https://sis-t.redsys.es:25443/sis/realizarPago';
//$clave='sq7HjrUOBfKmC576ILgskD5srU870gJ7';

// PRODUCCIÓN
//$url_tpvv='https://sis.redsys.es/sis/realizarPago';
$url_tpvv='https://tpvinstitucional.bbva.com/TLBB/tlbb/PagoInstController';
$clave='SNRebUeewc70OYyTiMMAXWlB63h/iTc3';

$Ds_Merchant_MerchantName		= 'AYUNTAMIENTO DE MOGAN';
$Ds_Merchant_MerchantCode		= '332153832';
$Ds_Merchant_Terminal	        = '001';
$Ds_Merchant_Currency		    = '978';
$Ds_Merchant_TransactionType	= '0';

// Valores dependientes del formulario
$Ds_Merchant_Order				= $_GET['referencia'];
$Ds_Merchant_Amount				= euro2cent($_GET['importe']);
$Ds_Merchant_ProductDescription	= 'Identificacion: '.$_GET['identificacion'].' Referencia: '.$_GET['referencia'];

// Ontenemos el número de operación (Ds_Merchant_Order)
$seq=Sql("SELECT nextval('numeoper_seq') AS numeoper");
$Ds_Merchant_Order=str_pad((int) $seq['numeoper'],12,"0",STR_PAD_LEFT);

// Valores de configuración propios del funcionamiento en la web de www.gestmogan.com
$Ds_Merchant_MerchantData	= $Ds_Merchant_ProductDescription.' Importe: '.$_GET['importe']; // Para ser incluidos en los datos enviados por la respuesta “on-line” al comercio


// Valores del TPV INSTITUCIONAL.
// (2017-11-07): Pasamos de TPV generico al TPV INSTITUCIONAL
//
$XWtxtS020tpviFuc = '344499728';
$XWtxtS010tpviEmisora = $Emisora;
$XWtxtS020tpviRefcobro = $_GET['referencia']; 
$XWtxtS030tpviIdenrecibo = $_GET['identificacion'];
$XWimpStpviImporte = $_GET['importe'];

$XWtxtS060tpviCodBarras = '90521'.$XWtxtS010tpviEmisora.$XWtxtS020tpviRefcobro.$XWtxtS030tpviIdenrecibo.str_pad($XWimpStpviImporte, 8, '0', STR_PAD_LEFT).'0';

//-----------------------------------------------------
// Insertamos el registro de la petición en la BD
//-----------------------------------------------------
$query="INSERT INTO regioper (numeoper, idenno60, refeno60, impo, tipopago, fechhora, sesion_id,
			ds_merchant_amount_out,
            ds_merchant_currency_out, ds_merchant_order_out, ds_merchant_productdescription_out,
            ds_merchant_merchantcode_out, ds_merchant_merchanturl_out, ds_merchant_urlok_out,
            ds_merchant_urlko_out, ds_merchant_merchantname_out, ds_merchant_merchantsignature_out,
            ds_merchant_terminal_out, ds_merchant_transactiontype_out, ds_merchant_merchantdata_out)
		VALUES ('$Ds_Merchant_Order', '{$_GET['identificacion']}', '{$_GET['referencia']}','{$_GET['importe']}','TPV', current_timestamp, '{$_SESSION['codisesi']}',
		        '$Ds_Merchant_Amount',
		        '$Ds_Merchant_Currency', '$Ds_Merchant_Order', '$Ds_Merchant_ProductDescription',
            '$Ds_Merchant_MerchantCode', '', '',
            '', '$Ds_Merchant_MerchantName', '$Ds_Signature',
            '$Ds_Merchant_Terminal', '$Ds_Merchant_TransactionType', '$Ds_Merchant_MerchantData'
		)";
Sql($query);

if (isset($_REQUEST['matricula']) && isset($_REQUEST['expediente'])){
    
    Sql("INSERT INTO zona_azul (numeoper, expediente, matricula) VALUES ('$Ds_Merchant_Order','{$_REQUEST['expediente']}','{$_REQUEST['matricula']}')");
}

//---------------------------------
// Envio del formulario
//---------------------------------
print("
	<body onload='form.submit();'>
		<form name=form action='$url_tpvv' method='post'>

			<input type='hidden' name='XWtxtS020tpviFuc' id='XWtxtS020tpviFuc' value='$XWtxtS020tpviFuc'>

            <input type='hidden' name='XWtxtS010tpviEmisora' id='XWtxtS010tpviEmisora' value='$XWtxtS010tpviEmisora'>
            <input type='hidden' name='XWtxtS020tpviRefcobro' id='XWtxtS020tpviRefcobro' value='$XWtxtS020tpviRefcobro'>
            <input type='hidden' name='XWtxtS030tpviIdenrecibo' id='XWtxtS030tpviIdenrecibo' value='$XWtxtS030tpviIdenrecibo'>
            <input type='hidden' name='XWimpStpviImporte' id='XWimpStpviImporte' value='$XWimpStpviImporte'>

			<input type='hidden' name='XWnumStpviAction' id='XWnumStpviAction' value='2'>

		</form>
");