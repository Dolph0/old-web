<?php
// 20140319: Grabación de datos remitidos por el TPV vrtual de RedSys tras el pago mendiante tarjeta
include "../database/func.inc";

$file=fopen("../logfile/retorno_tpv.log","a+");
fputs($file,"DATA:\n");
foreach($_POST as $clave => $valor) {
	fputs($file, "  Clave: ".$clave." Valor ".$valor);
	fputs($file,"\n");
}
fputs($file,"FIN DATA\n\n");

// Valores constantes facilitados por el banco
// ENTORNO DE PRUEBAS
//$url_tpvv='https://sis-t.redsys.es:25443/sis/realizarPago';
//$clave='sq7HjrUOBfKmC576ILgskD5srU870gJ7';

// PRODUCCIÓN
//$url_tpvv='https://sis.redsys.es/sis/realizarPago';
$clave='SNRebUeewc70OYyTiMMAXWlB63h/iTc3';

//------------------------------------------------------------------------
//-- Actualizacion de seguridad, cambiamos SHA1 ==>> HMAC SHA256 / JSON --
//------------------------------------------------------------------------
// Verificacion de la firma HMAC (Ds_Signature)
include_once "../API_PHP/redsysHMAC256_API_PHP_4.0.2/apiRedsys.php";
$my_Obj = new RedsysAPI;
$version = $_POST['Ds_SignatureVersion'];
$params  = $_POST['Ds_MerchantParameters'];
$signatureRecibida = $_POST['Ds_Signature'];

// Verificacion de la firma recibida
$decodec = $my_Obj->decodeMerchantParameters($params);
$signatureCalculada = $my_Obj->createMerchantSignatureNotif($clave,$params);

// Decodificacion de los parametros recibidos
$file=fopen("../logfile/retorno_tpv.log","a+");
fputs($file,"DATA DECODEC:\n");
fputs($file, "  Data decodec: ".$decodec);
fputs($file,"\n");
fputs($file,"FIN DATA DECODEC\n\n");

if ($signatureCalculada === $signatureRecibida) {
  // FIRMA OK.
  // Construimos el query
  $query="UPDATE regioper SET  
  				ds_transactiontype_in='".$my_Obj->getParameter('Ds_TransactionType')."',  
          ds_card_country_in='".$my_Obj->getParameter('Ds_Card_Country')."', 
  				ds_date_in='".$my_Obj->getParameter('Ds_Date')."',  
  				ds_hour_in='".$my_Obj->getParameter('Ds_Hour')."',  
  				ds_amount_in='".$my_Obj->getParameter('Ds_Amount')."',  
  				ds_currency_in='".$my_Obj->getParameter('Ds_Currency')."',
  				ds_order_in='".$my_Obj->getParameter('Ds_Order')."',  
  				ds_merchantcode_in='".$my_Obj->getParameter('Ds_MerchantCode')."',  
  				ds_terminal_in='".$my_Obj->getParameter('Ds_Terminal')."', 
  				ds_signature_in='".$signatureRecibida."', 
  				ds_response_in='".$my_Obj->getParameter('Ds_Response')."',
  				ds_merchantdata_in='".$my_Obj->getParameter('Ds_MerchantData')."',
  				ds_securepayment_in='".$my_Obj->getParameter('Ds_SecurePayment')."',
  				ds_authorisationcode_in='".$my_Obj->getParameter('Ds_AuthorisationCode')."',
  				ds_consumerlanguage_in='".$my_Obj->getParameter('Ds_ConsumerLanguage')."'
  		 WHERE regioper.ds_merchant_order_out='".$my_Obj->getParameter('Ds_Order')."'";
  Sql($query);
} else {
  // FIRMA INCORRECTA.
  $file=fopen("../logfile/retorno_tpv.log","a+");
  fputs($file,"DATA RESPONSE:\n");
	fputs($file, "  Ds_Order: ".$my_Obj->getParameter('Ds_Order').". ERROR EN LA FIRMA RECIBIDA EN LA RESPUESTA. ");
	fputs($file,"\n");
  fputs($file,"FIN DATA\n\n");
}

?>
