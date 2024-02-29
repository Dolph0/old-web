<?php
// 20131219: Envío de datos al TPV vrtual de RedSys para pago mendiante tarjeta
include "../euro.fnc";
include "../database/func.inc";

// Valores constantes facilitados por el banco
// ENTORNO DE PRUEBAS
//$url_tpvv='https://sis-t.redsys.es:25443/sis/realizarPago';
//$clave='sq7HjrUOBfKmC576ILgskD5srU870gJ7';

// PRODUCCIÓN
$url_tpvv='https://sis.redsys.es/sis/realizarPago';
$clave='SNRebUeewc70OYyTiMMAXWlB63h/iTc3';

$Ds_Merchant_MerchantName		= 'AYUNTAMIENTO DE MOGAN';
$Ds_Merchant_MerchantCode		= '332153832';
$Ds_Merchant_Terminal			= '001';
$Ds_Merchant_Currency			= '978';
$Ds_Merchant_TransactionType	= '0';

// Valores de configuración propios del funcionamiento en la web de www.gestmogan.com
$Ds_Merchant_MerchantURL	= 'http://86.109.165.141/pruebas_tpv/menu/pago_impuestos/retorno/get_posts.php'; // URL del comercio que recibirá un post con los datos de la transacción.
//$Ds_Merchant_UrlOK			= 'http://86.109.165.141/pruebas_tpv/menu/pago_impuestos/pago_ok.html'; // URL de retorno (SI el usuario pulsa el botón de Continuar) si el pagó se completó 
//$Ds_Merchant_UrlKO			= 'http://86.109.165.141/pruebas_tpv/menu/pago_impuestos/pago_ko.html';  // URL de retorno (SI el usuario pulsa el botón de Continuar) si el pago NO se completó
$Ds_Merchant_MerchantData	= 'Retorno ID: '.$_POST['identificacion'].' RF: '.$_POST['referencia']; // Para ser incluidos en los datos enviados por la respuesta “on-line” al comercio

// Valores dependientes del formulario
$Ds_Merchant_Order				= $_POST['referencia'];
$Ds_Merchant_Amount				= euro2cent($_POST['importe']);
$Ds_Merchant_ProductDescription	= 'Identificacion: '.$_POST['identificacion'].' Referencia: '.$_POST['referencia'];

// Insertamos el registro de la petición en la BD y obtenemos el Ds_Merchant_Order
$seq=Sql("SELECT nextval('numeoper.numeoper_seq') AS numeoper");
$Ds_Merchant_Order=str_pad((int) $seq['numeoper'],12,"0",STR_PAD_LEFT);
$query="INSERT INTO regioper (numeoper, idenno60, refeno60, impo, tipopago, fechhora) values ($Ds_Merchant_Order, '{$_POST[identificacion]}', '{$_POST[referencia]}','{$_POST[importe]}','TPV', current_timestamp)";
Sql($query);

// Firma de la transacción
// Con la nueva version HMAC SHA256, se firma el JSON
// (ver mas abajo)
// $message = $Ds_Merchant_Amount.$Ds_Merchant_Order.$Ds_Merchant_MerchantCode.$Ds_Merchant_Currency.$Ds_Merchant_TransactionType.$Ds_Merchant_MerchantURL.$clave;
// $Ds_Merchant_MerchantSignature = strtoupper(sha1($message));

//------------------------------------------------------------------------
//-- Actualizacion de seguridad, cambiamos SHA1 ==>> HMAC SHA256 / JSON --
//------------------------------------------------------------------------
// Nuevos campos para la comunicacion por JSON - HMAC SHA256
include_once "../API_PHP/redsysHMAC256_API_PHP_4.0.2/apiRedsys.php";
$my_Obj = new RedsysAPI;
$my_Obj->setParameter('Ds_Merchant_Amount', $Ds_Merchant_Amount);
$my_Obj->setParameter('Ds_Merchant_Currency', $Ds_Merchant_Currency);
$my_Obj->setParameter('Ds_Merchant_Order', $Ds_Merchant_Order);
$my_Obj->setParameter('Ds_Merchant_ProductDescription', $Ds_Merchant_ProductDescription);
$my_Obj->setParameter('Ds_Merchant_MerchantCode', $Ds_Merchant_MerchantCode);
$my_Obj->setParameter('Ds_Merchant_Terminal', $Ds_Merchant_Terminal);
$my_Obj->setParameter('Ds_Merchant_MerchantName', $Ds_Merchant_MerchantName);
$my_Obj->setParameter('Ds_Merchant_TransactionType', $Ds_Merchant_TransactionType);

$Ds_SignatureVersion   = 'HMAC_SHA256_V1';
$Ds_MerchantParameters = $my_Obj->createMerchantParameters();
$Ds_Signature          = $my_Obj->createMerchantSignature($clave);

print("
	<html>
	<head><title>Pago de tributos municipales</title></head>
	<body onload='form.submit();'>
		<form name=form action=$url_tpvv method=post >
			<input type=\"hidden\" name=\"Ds_SignatureVersion\" id=\"Ds_SignatureVersion\" value=\"$Ds_SignatureVersion\">

			<input type=\"hidden\" name=\"Ds_MerchantParameters\" id=\"Ds_MerchantParameters\" value=\"$Ds_MerchantParameters\">

			<input type=\"hidden\" name=\"Ds_Signature\" id=\"Ds_Signature\" value=\"$Ds_Signature\">
		</form>
	</body>
	</html>
");